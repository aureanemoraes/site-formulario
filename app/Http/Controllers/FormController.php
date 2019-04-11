<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Form;
use App\Question;
use App\Option;
use App\Oqf;
use App\Answer;
use App\Aqf;

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
        if ($request->input('duration') ) {
            $form->duration = Carbon::parse($request->input('duration'));
        }
        $form->user_id = Auth::user()->id;
        $form->save();
        $id = $form->id;

        return view('forms.show', compact('form'));
        //return redirect('show-form', ['id' => $form->id]);
    }

    public function show($id)
    {
        $form = Form::find($id); // encontra o fomulário
        $oqfs = Oqf::where('form_id', '=', $form->id)->get();
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

        $questions = array_unique($questions, SORT_REGULAR);
        usort($questions, function($a, $b) { return $a->id - $b->id; }); // organizar array pelo id da questão
        //array_sort($questions, 'created_at', SORT_ASC);
        $questions = (object)$questions;

        //$k = 1;

        $options = array_unique($options, SORT_REGULAR);
        $options = (object)$options;

        return view('forms.show', compact('questions', 'options', 'oqfs', 'form'));
        //return view('teste', compact('questions', 'options', 'oqfs'));
    }


    public function edit($id)
    {
        $form = Form::find($id);
        return view('forms.edit', compact('form'));
    }


    public function update(Request $request, $id)
    {
        $form = Form::find($id);
        $form->name = $request->input('name');
        $form->description = $request->input('description');
        $form->duration = Carbon::parse($request->input('duration'));
        $form->save();
        return redirect('/show-form/' . $form->id);
    }


    public function destroy($id)
    {
        Form::find($id)->delete();
        return back();
    }

    // função auxiliar para organizar array por ordem id
    function cmp($a, $b) {
        if ($a == $b) {
        return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    public function active($id) {
        Form::withTrashed()->find($id)->restore();
        return back();
    }

    public function showAll() {
        $allForms = Form::withTrashed()->where('user_id','=',Auth::user()->id)->orderBy('created_at','asc')->get();
        $countAllForms = count($allForms);

        $trashedForms = Form::onlyTrashed()->where('user_id','=',Auth::user()->id)->orderBy('created_at','asc')->get();
        $countTrashedForms = count($trashedForms);

        $forms = Form::where('user_id','=',Auth::user()->id)->orderBy('created_at','asc')->get();
        $countForms = count($forms);

        return view('all_forms', compact('allForms', 'countAllForms', 'countTrashedForms', 'countForms'));
    }

    public function showDeletedForms() {
        $allForms = Form::withTrashed()->where('user_id','=',Auth::user()->id)->orderBy('created_at','asc')->get();
        $countAllForms = count($allForms);

        $trashedForms = Form::onlyTrashed()->where('user_id','=',Auth::user()->id)->orderBy('created_at','asc')->get();
        $countTrashedForms = count($trashedForms);

        $forms = Form::where('user_id','=',Auth::user()->id)->orderBy('created_at','asc')->get();
        $countForms = count($forms);

        return view('deleted_forms', compact('trashedForms', 'countTrashedForms', 'countAllForms', 'countForms'));
    }
}
