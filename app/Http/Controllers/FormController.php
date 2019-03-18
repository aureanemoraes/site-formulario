<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Form;
use App\Question;
use App\Option;

class FormController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        return view('forms.new');
    }


    public function store(Request $request)
    {
        $form = new Form();
        $form->name = $request->input('name');
        $form->description = $request->input('description');
        $form->duration = Carbon::parse($request->input('duration'));
        $form->user_id = Auth::user()->id;
        $form->save();
        $id = $form->id;

        return view('forms.show', compact('form'));
        //return redirect('show-form', ['id' => $form->id]);
    }

    public function show($id)
    {
        $form = Form::find($id);
        $questions = Question::where('form_id', '=', $form->id)->get();
        $options = Option::all();
        //$result = [];
        //foreach ($questions as $question) {
        //    $getOptions = Option::where('question_id', '=', $question->id)->get();
        //}

        return view('forms.show', compact('form', 'questions', 'options'));
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {

    }


    public function destroy($id)
    {
        //
    }
}
