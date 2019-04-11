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
        $form = Form::withTrashed()->find($id);
        $i= 0;
        $oqfs = Oqf::where('form_id', '=', $id)->get(); // buscando todas as oqf do formulário
        $aqfs = Aqf::where('form_id', '=', $id)->get(); // buscando todas as aqf do formulário

        foreach($oqfs as $oqf) { // buscando todas as opções e questões (oqf) do formulário
            $questions[$i] = Question::find($oqf->question_id);
            $options[$i] = Option::find($oqf->option_id);
            $i++;
        }

        foreach($aqfs as $aqf) {
            $questions[$i] = Question::find($aqf->question_id);
        }

        $questions = array_unique($questions, SORT_REGULAR); // removendo valores duplicados do array
        usort($questions, function($a, $b) { return $a->id - $b->id; }); // organizar array pelo id da questão
        $questions = (object)$questions; // convertendo para objeto

        $options = array_unique($options, SORT_REGULAR); // removendo valores duplicados do array
        $options = (object)$options; // convertendo para objeto

        $i = 0;
        foreach($questions as $q) {
            if($q->type != 3) {
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
                        'width' => '100%',
                        'height' => '100%'
                ]);
                $i++;
            } else {
                $graphic[$i] = \Lava::DataTable();
                $graphic[$i]->addStringColumn('Opções')
                        ->addNumberColumn('Porcentagem');
                foreach($aqfs as $aqf ) {
                        $notAnswered = $form->amount - $aqf->amount_question;
                        $graphic[$i]->addRow(['Respondido (' . $aqf->amount_question .')', $aqf->amount_question]);
                        $graphic[$i]->addRow(['Não respondido (' . $notAnswered . ')', $notAnswered]);


                }
                    $piechart = \Lava::PieChart($q->name, $graphic[$i], [
                        'title' => $q->name,
                        'is3D' => true,
                        'pieSliceText' => 'percent',
                        'width' => '100%',
                        'height' => '100%'
                ]);
                $i++;
            }

        }

        return view('graphics.show', compact('questions', 'form'));
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
