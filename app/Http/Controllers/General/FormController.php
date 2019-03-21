<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Form;
use App\Question;
use App\Option;
use App\Oqf;
use App\Aqf;
use App\Answer;

class FormController extends Controller
{

    public function index()
    {
        //
    }

    public function create($id)
    {
        $form = Form::find($id); // encontra o formulário
        $oqfs = Oqf::where('form_id', '=', $form->id)->get(); // encontra as questões do formulário
        $i = 0;
        $j = 0;
        $questions = [];
        $options = [];
        $answers = [];

        foreach($oqfs as $oqf){
            $questions[$i] = Question::find($oqf->question_id);
            // encontrar as opções
            $options[$i] = Option::find($oqf->option_id);
            $i++;
        }


        // encontrar questões subjetivas
        $aqfs = Aqf::where('form_id', '=', $form->id)->get();
        foreach($aqfs as $aqf){
            $questions[$i] = Question::find($aqf->question_id);
            $i++;
        }

        usort($questions, function($a, $b) { return $b->id - $a->id; }); // organizar array pelo id da questão

        $questions = array_unique($questions, SORT_REGULAR);
        $questions = (object)$questions;



        $options = array_unique($options, SORT_REGULAR);
        $options = (object)$options;

        return view('form', compact('questions', 'options', 'oqfs', 'form'));
    }

    public function store(Request $request)
    {
        $count = count($request->all());
        $name= "op";
        for ($i=0; $i <= $count-2; $i++){
            $option = Option::find($request->input($name . $i));
            $option->amount ++;
            $option->save();
        }
        return view('teste', compact('count'));
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
