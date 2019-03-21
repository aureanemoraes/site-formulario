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
                    $option->name = $value;
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
    }

    function question_exists($oqf) {
        // verificar se a questão já existe na base de dados
    }
}
