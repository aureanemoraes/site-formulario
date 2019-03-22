<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Option;
use App\Oqf;
use App\Answer;
use App\Aqf;

class QuestionController extends Controller
{
    public function create($id) // adicionar questão agora está pelo forms.show
    {
        return view('questions.new', compact('id'));
    }

    public function store(Request $request) // pronto
    {
        $required = "";

        //$id = $request->input('form_id');
        $question = new Question();
        $question->name = $request->input('name');
        $question->type = $request->input('type');
        if( $request->input('description') ) {
            $question->description = $request->input('description');
        }
        $question->save();

        // verificando questão obrigatória
        if($request->input("required") == 1) {
            $required = $question->id;
        }
        if(($request->input('type') == 1) || ($request->input('type') == 2)) {
            $options = explode(",", $request->input('options'));
            foreach ($options as $option => $value) {
                $slug = $this->criar_slug($value);
                $optionExists = Option::where('slug', '=', $slug)->first();
                if( $optionExists == "") {
                    $option = new Option();
                    $option->name = trim($value);
                    $option->slug = $slug;
                    $option->save();

                    $oqf = new Oqf();
                    $oqf->option_id = $option->id;
                    $oqf->form_id = $request->input('form_id');
                    $oqf->question_id = $question->id;
                    $oqf->save();
                } else {
                    // verificando se a primary key da questão já existe
                    $primaryOqf = Oqf::where('question_id', '=', $question->id)
                                ->where('option_id', '=', $optionExists->id)
                                ->get();

                    if($primaryOqf == null) {
                        $oqf = new Oqf();
                        $oqf->option_id = $optionExists->id;
                        $oqf->form_id = $request->input('form_id');
                        $oqf->question_id = $question->id;
                        $oqf->save();
                    } else {
                        $questions = Question::find($question->id);
                        $questions->delete();
                        return redirect('/show-form/' . $request->input('form_id'))->with('data', [$primaryOqf]);
                    }
                }
            }
        } else {
            $aqf = new Aqf();
            $aqf->question_id = $question->id;
            $aqf->form_id = $request->input('form_id');
            $aqf->save();
        }

        return redirect('/show-form/' . $request->input('form_id'), compact('required'));
    }

    public function show($id) // alterar para nova estrutura do db
    {

    }

    function criar_slug($name) { // pronto
        $name = trim($name);
        $procurar =   ['ã','â','ê','é','í','õ','ô','ú',' ','?'];
        $substituir = ['a','a','e','e','i','o','o','u','-',''];
        return str_replace($procurar, $substituir, mb_strtolower($name));
    }

    function edit($id) {
        $question = Question::find($id);
        $oqfs = Oqf::where('question_id', '=', $question->id)->get();
        $options = [];
        $i = 0;

        foreach($oqfs as $oqf) {
            $options[$i] = Option::find($oqf->option_id);
            $i++;
        }

        $options = array_unique($options, SORT_REGULAR);
        usort($options, function($a, $b) { return $a->id - $b->id; }); // organizar array pelo id da questão
        $options = (object)$options;

        return view('questions.edit', compact('question', 'options'));
    }

    function update($id, Request $request) { // NÃO FINALIZADO
        $question = Question::find($id);
        $form = $this->findForm_id($id, $request->input('type'));
        $question->name = $request->input('name');
        $question->description = $request->input('description');
        // verificar se o type da question foi modificado
        if ($question->type == $request->input('type')) {
            if(($request->input('type') == 1) || ($request->input('type') == 2)) {
                $this->change_options($id, $request->input('options'));
                } else {
                    if($request->input('options') != "") {
                        return redirect('/edit-question/' . $id)->with('data', '1');
                    }
                }
        } else {
            switch ($request->input('type')) {
                case 1:
                case 2:
                // se a questão for modificada de discursiva -> objetiva, excluir primary key da tabela aqf e adicionar primary key na tabela oqf
                // verificar se a primary key já existiu no trashed, se sim, recuperar a linha

                // verificar a oqf table com o withTrashed
                // if deleted_at == null
                // adiciona um novo oqf
                // se não
                // restore na linha

                    $this->change_options($question->id, $request->input('options'));
                break;
                case 3:
                    if($request->input('options') != "") {
                        return redirect('/show-form/' . $form->form_id)->with('data', '1');
                    }
                    // se a questão for modificada para discursiva, excluir a primary key da tabela oqf e adicionar na tabela aqf FEITO
                    $oqfs = Oqf::where('question_id', '=', $question->id)->get();
                    foreach($oqfs as $oqf) {
                        $oqf->where('option_id', '=', $oqf->option_id)
                        ->where('question_id', '=', $oqf->question_id)
                        ->where('form_id', '=', $oqf->form_id)
                        ->delete();
                    }

                    $aqf = new Aqf();
                    $aqf->question_id = $id;
                    $aqf->form_id = $form->form_id;
                    $aqf->save();

                break;
            }
            $question->type = $request->input('type');

        }
        $question->save();
        return redirect('/show-form/' . $form->form_id);

    }

    // função auxiliar para organizar array por ordem id
    function cmp($a, $b) {
        if ($a == $b) {
        return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    function change_options($question_id, $request_options) {
        $options_id = [];
        $i = 0;
        // descobrir o id das opções que serão editadas
        $oqfs = Oqf::where('question_id', '=', $question_id)->get();
        foreach ($oqfs as $oqf) {
            $options_id[$i] = $oqf->option_id; // salvar os ids num array
            $i++;
        }
        $options = explode(",", $request_options);
        $j = 0;
        foreach ($options as $option => $value) {
            $slug = $this->criar_slug($value);
            $option = Option::find($options_id[$j]); // encontrar as options
            $option->name = trim($value);
            $option->slug = $slug;
            $option->save();
            // se a opção for modificada, na tabela oqf mudar o amount para 0
            $oqf = Oqf::where('option_id', '=', $option->id)->first();
            $oqf->amount_question = 0;
            $oqf->save();
            $j++;
            }
    }

    // pegar o form_id
    function findForm_id($question_id, $request_type) {
        if($request_type == 1 || $request_type == 2) {
            $form = Oqf::where('question_id', '=', $question_id)->first();
        } else {
            $form = Aqf::where('question_id', '=', $question_id)->first();
        }
        return $form;
    }

}
