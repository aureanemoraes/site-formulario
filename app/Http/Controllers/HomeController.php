<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Form;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $allForms = Form::withTrashed()->where('user_id','=',Auth::user()->id)->orderBy('created_at','desc')->get();
        $countAllForms = count($allForms);

        $trashedForms = Form::onlyTrashed()->where('user_id','=',Auth::user()->id)->orderBy('created_at','desc')->get();
        $countTrashedForms = count($trashedForms);

        $forms = Form::where('user_id','=',Auth::user()->id)->orderBy('created_at','desc')->get();
        $countForms = count($forms);
        return view('active_forms', compact('forms', 'countForms',
                                            'allForms', 'countAllForms',
                                            'trashedForms', 'countTrashedForms'));
    }
}
