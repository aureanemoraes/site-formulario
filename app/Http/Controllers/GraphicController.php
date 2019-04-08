<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;

use App\Form;
use App\Option;
use App\Question;
use App\Oqf;
use App\Aqf;
use App\Answer;

class GraphicController extends Controller
{
    public function show($id)
    {
        // $actualQuestionStart = 0;
        // $actualQuestionEnd = 0;
        $i= 0;
        $oqfs = Oqf::where('form_id', '=', $id)->get(); // buscando todas as oqf do formulário
        $aqfs = Aqf::where('form_id', '=', $id)->get(); // buscando todas as aqf do formulário

        foreach($oqfs as $oqf) { // buscando todas as opções e questões do formulário
            $questions[$i] = Question::find($oqf->question_id);
            $options[$i] = Option::find($oqf->option_id);
            $i++;
        }

        $questions = array_unique($questions, SORT_REGULAR); // removendo valores duplicados do array
        usort($questions, function($a, $b) { return $a->id - $b->id; }); // organizar array pelo id da questão
        $questions = (object)$questions; // convertendo para objeto

        $options = array_unique($options, SORT_REGULAR); // removendo valores duplicados do array
        $options = (object)$options; // convertendo para objeto

        $i = 0;
        foreach($questions as $q) {
            $graphic[$i] = \Lava::DataTable();
            $graphic[$i]->addStringColumn('Opções')
                    ->addNumberColumn('Porcentagem');
            foreach($oqfs as $oqf ) {

                foreach($options as $o) {
                    if(($oqf->question_id == $q->id) &&($oqf->option_id == $o->id))
                    $graphic[$i]->addRow([$o->name, $oqf->amount_question]);
                }


            }
                $piechart = \Lava::PieChart($q->name, $graphic[$i], [
                    'title' => $q->name,
                    'is3D' => false,
                    'pieSliceText' => 'percentage',
                    'width' => '500'
            ]);
            $i++;
        }





        // if($oqfs != "") {
        //     foreach($oqfs as $oqf) {
        //         $question = $oqf->question_id; // 2
        //         if($actualQuestionStart != $question) { // 1  2
        //             $actualQuestionStart = $question; // 2
        //             $graphic[$i] = \Lava::DataTable();
        //             $graphic[$i]->addStringColumn('Opções')
        //                         ->addNumberColumn('Porcentagem');
        //         }
        //         $option = Option::find($oqf->option_id);
        //         $graphic[$i]->addRow([$option->name, $oqf->amount_question]);

        //         if($actualQuestionEnd != $question) {
        //             $actualQuestionEnd = $question;
        //             $question = Question::find($oqf->question_id);
        //             $questionss[$i] = $question;
        //             $piechart = \Lava::PieChart($question->name, $graphic[$i], [
        //                 'title' => $question->name,
        //                 'is3D' => true
        //             ]);
        //             $i++;
        //         }
        //             // do {

        //             // }while($actualQuestion != $oqf->question_id); // 1 //2
        //     }
        //     $questions = (object)$questionss;
        // }
        //$options = (object)$options;


        // $question = Question::where('form_id', '=', $id)->get();
        // $opts = Option::all();
        // $i=0;


        // foreach($question as $q) {
        //     $options[$i] = \Lava::DataTable();
        //     $options[$i]->addStringColumn('Opções')
        //             ->addNumberColumn('Porcentagem');
        //     foreach($opts as $o ) {
        //         if($o->question_id == $q->id) {
        //             $options[$i]->addRow([$o->name, $o->amount]);
        //         }
        //     }
        //         $piechart = \Lava::PieChart($q->name, $options[$i], [
        //             'title' => $q->name,
        //             'is3D' => true
        //     ]);
        //     $i++;
        // }
        return view('graphics.show', compact('questions'));
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
