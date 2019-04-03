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
        $question->name = trim($request->input('name'));
        $question->type = $request->input('type');
        if( $request->input('description') ) {
            $question->description = trim($request->input('description'));
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
                    $option->name = trim($value);
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

        if ( $question->name != trim($request->input('name')) ) { // verificando se o NAME da questão foi modificado
            $question->name = $request->input('name'); // modificando o NAME da questão
        }

        if ($question->description != trim($request->input('description')) ) { // verificando se a DESCRIPTION da questão foi modificada
            $question->description = $request->input('description'); // modificando a DESCRIPTION
        }


        if ($question->type == $request->input('type')) { // verificando se o TYPE da question foi modificado
            if(($request->input('type') == 1) || ($request->input('type') == 2)) { // verificando se o TYPE modificado da questão é 1 ou 2
                if( $request->input('options') == "" ) { // verificando se o campo options está vazio
                    return redirect('/edit-question/' . $id)->with('data', '1'); // exibindo mensagem de alerta, solicitando o preenchimento.
                } else {
                    // POSSIVEIS SITUAÇÕES DE MODIFICAÇÕES EM OPÇÕES
                    // ALTERAR SOMENTE OS VALORES DAS OPÇÕES
                    // ALTERAR O NÚMERO DAS OPÇÕES: DIMINUIR OU AUMENTAR
                    // ALTERAR O NÚMERO DAS OPÇÕES E OS VALORES
                    // OPÇÕES QUE JÁ EXISTEM E NOVAS OPÇÕES

                    // sempre que o usuário utilizar o update, apagar as oqf da tabela e criar novas
                    // isto soluciona o problema de alteração no número das questões e da possibilidade de
                    $oqfs = Oqf::where('question_id', '=', $question->id)->delete();

                    $options = explode( ",", $request->input('options') ); // armazenando as opções em um objeto
                    foreach ($options as $option => $value) {
                        $slug = $this->criar_slug($value); // criando o slug da option
                        $optionExists = Option::where('slug', '=', $slug)->first(); // verificando se esta option já existe na tabela Options
                        if ($optionExists != "") {
                            // verificiar se a primary key da oqf que será criada já existe
                            $oqfExists = Oqf::where('option_id', '=', $optionExists->id)
                                            ->where('question_id', '=', $question->id)
                                            ->where('form_id', '=', $form);
                            if ($oqfExists != "") {
                                return redirect('/edit-question/' . $id)->with('data', '3');
                            } else {
                                // criando a nova oqf
                                $oqf = new Oqf();
                                $oqf->option_id = $optionExists->id;
                                $oqf->question_id = $question->id;
                                $oqf->form_id = $form;
                                $oqf->save();
                            }
                        } else {
                            // criando a nova option
                            $newOption = new Option();
                            $newOption->name = trim($value);
                            $newOption->slug = $slug;
                            $newOption->save();
                            // criando a nova oqf
                            $oqf = new Oqf();
                            $oqf->option_id = $newOption->id;
                            $oqf->question_id = $question->id;
                            $oqf->form_id = $form;
                            $oqf->save();
                        }

                    }
                }

            }
        }
        return redirect('/show-form/' . $form);
    }
                //$this->change_options($id, $request->input('options'), $form);


        //         $options_id = [];
        //         $i = 0;
        //         // descobrir o id das opções que serão editadas
        //         $oqfs = Oqf::where('question_id', '=', $question->id)->get();
        //         foreach ($oqfs as $oqf) {
        //             $options_id[$i] = $oqf->option_id; // salvar os ids num array
        //             $i++;
        //         }

        //         // return view('teste', compact('options_id'));
        //         \Log::info($options_id);

        //         $options = explode(",", $request->input('options'));
        //         $j = 0;
        //         foreach ($options as $option => $value) {
        //             $slug = $this->criar_slug($value);
        //             $optionExists = Option::where('slug', '=', $slug)->first();
        //             if( $optionExists == "") {
        //                 $option = new Option();
        //                 $option->name = trim($value);
        //                 $option->slug = $slug;
        //                 $option->save();

        //                 $oqf = new Oqf();
        //                 $oqf->option_id = $option->id;
        //                 $oqf->form_id = $form;
        //                 $oqf->question_id = $question->id;
        //                 $oqf->save();
        //             } else {
        //                 // verificando se a primary key da questão já existe
        //                 $primaryOqf = Oqf::where('question_id', '=', $question->id)
        //                             ->where('option_id', '=', $optionExists->id)
        //                             ->first();

        //                 if($primaryOqf == "") {
        //                     $option = Option::find($optionExists->id); // encontrar as options
        //                     $option->name = trim($value);
        //                     $option->slug = $slug;
        //                     $option->save();
        //                     // se a opção for modificada, na tabela oqf mudar o amount para 0
        //                     $oqf = new Oqf();
        //                     $oqf->option_id = $optionExists->id;
        //                     $oqf->question_id = $question->id;
        //                     $oqf->form_id = $form;
        //                     $oqf->save();


        //                  } else {

        //                  }
        //             }
        //             $j++;
        //         }




        //         } else {
        //             if($request->input('options') != "") {
        //                 return redirect('/edit-question/' . $id)->with('data', '2');
        //             }
        //         }
        //} //else {
        //     switch ($request->input('type')) {
        //         case 1:
        //         case 2:
        //         // se a questão for modificada de discursiva -> objetiva, excluir primary key da tabela aqf e adicionar primary key na tabela oqf
        //         // verificar se a primary key já existiu no trashed, se sim, recuperar a linha
        //             $aqf = Aqf::where('question_id', '=', $question->id)
        //                         ->where('form_id', '=', $form)->first();
        //             if ($aqf) {
        //                 $aqf->delete();
        //                 $this->newOqf($question->id, $request->input('options'), $form);
        //             } else {
        //                 $x = $this->change_options($question->id, $request->input('options'), $form);
        //                 if ($x == 2) {
        //                     return redirect('/edit-question/' . $question->id)->with('data', '3');
        //                 }
        //             }

        //         // verificar a oqf table com o withTrashed
        //         // if deleted_at == null
        //         // adiciona um novo oqf
        //         // se não
        //         // restore na linha


        //         break;
        //         case 3:
        //             if($request->input('options') != "") {
        //                 return redirect('/edit-question/' . $id)->with('data', '1');
        //             }
        //             // se a questão for modificada para discursiva, excluir a primary key da tabela oqf e adicionar na tabela aqf FEITO
        //             $oqfs = Oqf::where('question_id', '=', $question->id)->get();
        //             foreach($oqfs as $oqf) {
        //                 $oqf->where('option_id', '=', $oqf->option_id)
        //                 ->where('question_id', '=', $oqf->question_id)
        //                 ->where('form_id', '=', $oqf->form_id)
        //                 ->delete();
        //             }

        //             $aqf = new Aqf();
        //             $aqf->question_id = $id;
        //             $aqf->form_id = $form;
        //             $aqf->save();

        //         break;
        //     }
        //     $question->type = $request->input('type');

        // }
        // $question->save();
        // return redirect('/show-form/' . $form);



    // função auxiliar para organizar array por ordem id
    function cmp($a, $b) {
        if ($a == $b) {
        return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    function change_options($question_id, $request_options, $form_id) {
        $options_id = [];
        $i = 0;
        // descobrir o id das opções que serão editadas
        $oqfs = Oqf::where('question_id', '=', $question_id)->get();
        foreach ($oqfs as $oqf) {
            $options_id[$i] = $oqf->option_id; // salvar os ids num array
            $i++;
        }

        // \Log::info($options_id);

        $options = explode(",", $request_options);
        $j = 0;
        foreach ($options as $option => $value) {
            $slug = $this->criar_slug($value);
            $optionExists = Option::where('slug', '=', $slug)->first();
            if( $optionExists == "") {
                $option = new Option();
                $option->name = trim($value);
                $option->slug = $slug;
                $option->save();

                $oqf = new Oqf();
                $oqf->option_id = $option->id;
                $oqf->form_id = $form_id;
                $oqf->question_id = $question_id;
                $oqf->save();
            } else {
                // verificando se a primary key da questão já existe
                $primaryOqf = Oqf::where('question_id', '=', $question_id)
                            ->where('option_id', '=', $optionExists->id)
                            ->first();

                if($primaryOqf == "") {
                    $option = Option::find($options_id[$j]); // encontrar as options
                    $option->name = trim($value);
                    $option->slug = $slug;
                    $option->save();
                    // se a opção for modificada, na tabela oqf mudar o amount para 0
                    // ARRUMAR, AQUI CRIA-SE UM NOVO OQF POIS ELA NÃO EXISTE
                    $oqf = Oqf::where('option_id', '=', $option->id)->first();
                    $oqf->amount_question = 0;
                    $oqf->save();

                 } else {

                 }
            }
            $j++;
        }
        return 1;
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

    function newOqf($id, $request_options, $form_id) {
        $options = explode(",", $request_options);
        foreach ($options as $option => $value) {
            $slug = $this->criar_slug($value);
            $optionExists = Option::where('slug', '=', $slug)->first();
            if( $optionExists == "") {
                $option = new Option();
                $option->name = trim($value);
                $option->slug = $slug;
                $option->save();

                $oqf = new Oqf();
                $oqf->option_id = $option->id;
                $oqf->form_id = $form_id;
                $oqf->question_id = $id;
                $oqf->save();
            } else {
                // verificando se a primary key da questão já existe
                $primaryOqf = Oqf::where('question_id', '=', $id)
                            ->where('option_id', '=', $optionExists->id)
                            ->first();

                if($primaryOqf == "") {
                    $oqf = new Oqf();
                    $oqf->option_id = $optionExists->id;
                    $oqf->form_id = $form_id;
                    $oqf->question_id = $id;
                    $oqf->save();

                 } else {
                    return 2;
                 }
            }
        }
        return 1;
    }

}
