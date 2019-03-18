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
        $question = Question::where('form_id', '=', $id);
        $opts = Option::all();

        $lava = new Lavacharts;

        $options = \Lava::DataTable();

        $options->addStringColumn('Opções')
                ->addNumberColumn('Porcentagem');
        foreach($question as $q) {
            foreach($opts as $o) {
                if($o->question_id == $question->id) {
                    $options->addRow([$o->name, $o->amount]);
                }
            }
            $piechart = \Lava::PieChart($q->name, $options, [
                'title' => 'Questões respondidas',
                'is3D' => true
            ]);
        }
        return view('graphics.show', compact('question', 'piechart'));
    }
}
