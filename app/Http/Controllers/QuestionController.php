<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Option;

class QuestionController extends Controller
{
    public function index()
    {
        //
    }

    public function create($id)
    {
        return view('questions.new', compact('id'));
    }

    public function store(Request $request)
    {
        $question = new Question();

        $question->name = $request->input('name');
        $question->description = $request->input('description');
        $question->form_id = $request->input('form_id');
        $question->save();

        $options = explode(",", $request->input('options'));
        foreach ($options as $option => $value) {
          $option = new Option();
          $option->name = $value;
          $option->question_id = $question->id;
          $option->save();
        }

        //return view('questions.show', compact('question', 'options'));
        return redirect('/show-form/' . $question->form_id);
    }

    public function show($id)
    {
        $question = Question::find($id);
        $ops = Option::all();
        $options = \Lava::DataTable();
        $options->addStringColumn('Opções')
                ->addNumberColumn('Porcentagem');

        foreach($ops as $op) {
            if( $op->question_id == $id) {
                $options->addRow([$op->name, $op->amount]);
            }
        }
        $piechart = \Lava::PieChart($question->name, $options, [
            'title' => 'Questões respondidas',
            'is3D' => true
        ]);
        return view('questions.show', compact('question', 'piechart'));
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
