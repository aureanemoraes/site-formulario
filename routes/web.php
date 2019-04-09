<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/form/{id}', 'General\FormController@create');
Route::post('/form/save', 'General\FormController@store');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group( function() {
    //form
    Route::get('/all-forms', 'FormController@showAll');
    Route::get('/deleted-forms', 'FormController@showDeletedForms');
    Route::get('/new-form', 'FormController@create');
    Route::post('/new-form', 'FormController@store');
    Route::get('/show-form/{id}', 'FormController@show');
    Route::get('/edit-form/{id}', 'FormController@edit');
    Route::put('/edit-form/{id}', 'FormController@update');
    Route::get('/delete-form/{id}', 'FormController@destroy');
    Route::get('/active-form/{id}', 'FormController@active');
    // question
    Route::get('/new-question/{id}', 'QuestionController@create');
    Route::post('/new-question/save', 'QuestionController@store');
    Route::get('/show-question/{id}', 'QuestionController@show');
    Route::get('/edit-question/{id}', 'QuestionController@edit');
    Route::put('/edit-question/{id}', 'QuestionController@update');

    // graphic
    Route::get('/show-graphic/{id}', 'GraphicController@show');
    Route::get('/show-graphic/question/{id}', 'GraphicController@show_question');
    // Routes - todos os usuários de level:0
    Route::middleware(['level:0'])->group( function() {
    });
    // Routes - todos os usuários de level:1
    Route::middleware(['level:1'])->group( function() {

    });
});
