<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;

use App\Form;
use App\Option;
use App\Question;

class GraphicController extends Controller
{
    public function show($id)
    {
        $question = Question::where('form_id', '=', $id)->get();
        $opts = Option::all();
        $i=0;


        foreach($question as $q) {
            $options[$i] = \Lava::DataTable();
            $options[$i]->addStringColumn('Opções')
                    ->addNumberColumn('Porcentagem');
            foreach($opts as $o ) {
                if($o->question_id == $q->id) {
                    $options[$i]->addRow([$o->name, $o->amount]);
                }
            }
                $piechart = \Lava::PieChart($q->name, $options[$i], [
                    'title' => $q->name,
                    'is3D' => true
            ]);
            $i++;
        }
        return view('graphics.show', compact('question'));
    }

    public function show_question($id) {
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
}
