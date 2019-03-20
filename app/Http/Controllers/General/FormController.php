<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Form;
use App\Question;
use App\Option;

class FormController extends Controller
{

    public function index()
    {
        //
    }

    public function create($id)
    {
        $form = Form::find($id);
        $questions = Question::where('form_id', '=', $form->id)->get();
        $options = Option::all();
        //$result = [];
        //foreach ($questions as $question) {
        //    $getOptions = Option::where('question_id', '=', $question->id)->get();
        //}

        return view('form', compact('form', 'questions', 'options'));
    }

    public function store(Request $request)
    {
        $count = count($request->input());
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
