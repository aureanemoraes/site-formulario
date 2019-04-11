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
        $question->name = ucfirst(trim($request->input('name')));
        $question->type = $request->input('type');
        if($request->input('required')) {
            $question->required = $request->input('required');
        } else {
            $question->required = 0;
        }

        if( $request->input('description') ) {
            $question->description = ucfirst(trim($request->input('description')));
        }
        $question->save();

        if(($request->input('type') == 1) || ($request->input('type') == 2)) {
            $options = explode(",", $request->input('options'));
            foreach ($options as $option => $value) {
                $slug = $this->criar_slug($value);
                $optionExists = Option::where('slug', '=', $slug)->first();
                if( $optionExists == "") {
                    $option = new Option();
                    $option->name = ucfirst(trim($value));
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
                                ->first();

                    if($primaryOqf == "") {
                        $oqf = new Oqf();
                        $oqf->option_id = $optionExists->id;
                        $oqf->form_id = $request->input('form_id');
                        $oqf->question_id = $question->id;
                        $oqf->save();
                    } else {
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

        return redirect('/show-form/' . $request->input('form_id'));
    }

    public function destroy($fid, $qid)
    {
        $question = Question::find($qid);
        if($question->type != 3) {
            $oqfs = Oqf::where('question_id', '=', $qid)
                        ->where('form_id', '=', $fid)->get();
            foreach($oqfs as $oqf) {
                $oqf->delete();
            }
        } else {
            $aqf = Aqf::where('question_id', '=', $qid)
                        ->where('form_id', '=', $fid)->first();
            $aqf->delete();
            $answers = Answer::where('question_id', '=', $qid)
                                ->where('form_id', '=', $fid)->get();
            if($answers != "") {
                foreach($answers as $answer) {
                    $answer->delete();
                }
            }
        }
        $question->delete();
        return back();
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
        $question = Question::find($id); // encontrando a question
        $form = $this->findForm_id($id, $question->type); // encontrando o formulário da questão

        if ( $question->name != ucfirst(trim($request->input('name'))) ) { // verificando se o NAME da questão foi modificado
            $question->name = $request->input('name'); // modificando o NAME da questão
        }

        if( $question->required != $request->input('required')) { // verificando se a questão mudou o status de obrigatoriedade
            $question->required = $request->input('required');
        }

        if ($question->description != ucfirst(trim($request->input('description'))) ) { // verificando se a DESCRIPTION da questão foi modificada
            $question->description = $request->input('description'); // modificando a DESCRIPTION
        }

        if ( $question->type == $request->input('type') ) { // verificando se o TYPE da question foi modificado
            $question->save();
            if(($request->input('type') == 1) || ($request->input('type') == 2)) { // verificando se o TYPE modificado da questão é 1 ou 2
                    $result = $this->changeOQF($question->id, $request->input('options'), $form);
                    switch($result) {
                        case 1:
                            return redirect('/edit-question/' . $question->id)->with('data', '1');
                        break;
                        case 3:
                            return redirect('/edit-question/' . $question->id)->with('data_3', '3');
                        break;
                        case 0:
                            return redirect('/show-form/' . $form);
                        break;
                    }
            } else { // se a questão for do tipo 3 (discursiva), somente verificar se o campo opções está preenchido
                if($request->input('options') != "") {
                    return redirect('/edit-question/' . $question->id)->with('data_2', '2');
                } else {
                    return redirect('/show-form/' . $form);
                }
            }
        } else { // alterações se o type da questão foi modificado
            switch($request->input('type')) {
                case 1:
                case 2:
                    if($question->type != 3) {
                        $result = $this->changeOQF($question->id, $request->input('options'), $form);
                        switch($result) {
                            case 1:
                                return redirect('/edit-question/' . $question->id)->with('data', '1');
                            break;
                            case 3:
                                return redirect('/edit-question/' . $question->id)->with('data_3', '3');
                            break;
                            case 0:
                                $question->type = $request->input('type');
                                $question->save();
                                return redirect('/show-form/' . $form);
                            break;
                        }
                    } else {
                        // se a questão for modificada de discursiva -> objetiva, excluir primary key da tabela aqf e adicionar primary key na tabela oqf
                        $result = $this->changeOQF($question->id, $request->input('options'), $form);
                        switch($result) {
                            case 1:
                                return redirect('/edit-question/' . $question->id)->with('data', '1');
                            break;
                            case 3:
                                $aqf = Aqf::where('question_id', '=', $question->id)
                                ->where('form_id', '=', $form)->first();
                                $aqf->delete();
                                $question->type = $request->input('type');
                                $question->save();
                                return redirect('/edit-question/' . $question->id)->with('data_3', '3');
                            break;
                            case 0:
                                $aqf = Aqf::where('question_id', '=', $question->id)
                                            ->where('form_id', '=', $form)->first();
                                $aqf->delete();
                                $question->type = $request->input('type');
                                $question->save();
                                return redirect('/show-form/' . $form);
                            break;
                        }
                    }
                break;
                case 3:
                    // se a questão for modificada de objetiva para discursiva, excluir as primary key oqfs
                    // e criar a primary key aqfs
                    if($request->input('options') != "") {
                        return redirect('/edit-question/' . $question->id)->with('data_2', '2');
                    } else {
                        $oqfs = Oqf::where('question_id', '=', $question->id)
                        ->where('form_id', '=', $form)->get();
                        foreach($oqfs as $oqf) {
                            $oqf->delete();
                        }

                        $question->type = $request->input('type');
                        $question->save();

                        $aqf = new Aqf();
                        $aqf->question_id = $question->id;
                        $aqf->form_id = $form;
                        $aqf->save();
                        return redirect('/show-form/' . $form);
                    }
                break;
            }
        }
    }

    // função auxiliar para organizar array por ordem id
    function cmp($a, $b) {
        if ($a == $b) {
        return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    // pegar o form_id
    function findForm_id($question_id, $request_type) {
        if($request_type == 1 || $request_type == 2) {
            $form = Oqf::where('question_id', '=', $question_id)->first();
            $form_id = $form->form_id;
        } else {
            $form = Aqf::where('question_id', '=', $question_id)->first();
            $form_id = $form->form_id;
        }
        return $form_id;
    }

    function changeOQF ($questionID, $requestOPTIONS, $formID) {
        // POSSIVEIS SITUAÇÕES DE MODIFICAÇÕES EM OPÇÕES
        // ALTERAR SOMENTE OS VALORES DAS OPÇÕES
        // ALTERAR O NÚMERO DAS OPÇÕES: DIMINUIR OU AUMENTAR
        // ALTERAR O NÚMERO DAS OPÇÕES E OS VALORES
        // OPÇÕES QUE JÁ EXISTEM E NOVAS OPÇÕES

        // sempre que o usuário utilizar o update, apagar as oqf da tabela e criar novas
        // isto soluciona o problema de alteração no número das questões e da possibilidade de
        if($requestOPTIONS != "") {
            $oqfs = Oqf::where('question_id', '=', $questionID)->delete();
            $options = explode( ",", $requestOPTIONS ); // armazenando as opções em um objeto
            foreach ($options as $option => $value) {
                if ($value != "") {
                    $slug = $this->criar_slug($value); // criando o slug da option
                    $optionExists = Option::where('slug', '=', $slug)->first(); // verificando se esta option já existe na tabela Options
                    if ($optionExists != "") {
                        // verificiar se a primary key da oqf que será criada já existe
                        $oqfExists = Oqf::where('option_id', '=', $optionExists->id)
                                        ->where('question_id', '=', $questionID)
                                        ->where('form_id', '=', $formID)->first();
                        if ($oqfExists != "") {
                            // return redirect('/edit-question/' . $questionID)->with('data_3', '3');
                            return 3;
                        } else {
                            // criando a nova oqf
                            $oqf = new Oqf();
                            $oqf->option_id = $optionExists->id;
                            $oqf->question_id = $questionID;
                            $oqf->form_id = $formID;
                            $oqf->save();
                        }
                    } else {
                        // criando a nova option
                        $newOption = new Option();
                        $newOption->name = ucfirst(trim($value));
                        $newOption->slug = $slug;
                        $newOption->save();
                        // criando a nova oqf
                        $oqf = new Oqf();
                        $oqf->option_id = $newOption->id;
                        $oqf->question_id = $questionID;
                        $oqf->form_id = $formID;
                        $oqf->save();
                    }
                }
            }
        } else {
            // return redirect('/edit-question/' . $questionID)->with('data', '1');
            return 1;
        }
        return 0;
        // return redirect('/show-form/' . $formID);
    }
}
