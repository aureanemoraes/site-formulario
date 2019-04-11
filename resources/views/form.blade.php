@extends('layouts.app')

@section('content')
@php($i = 1)
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header ">
                    <h2>{{ ucwords($form->name) }}</h2>
                    <h4>{{$form->description}}</h4>
                </div>
                <div class="card-body">
                    <form action="/form/save" method="POST">
                        @csrf
                            @if(isset($questions) && isset($options) && isset($oqfs))
                            @foreach($questions as $question)
                                @if($question->required == 1)
                                    <label>{{$i . ')'}}</label>
                                    <label>{{$question->name}}</label>
                                    <label>{{$question->description}}</label>
                                    <small class="text-danger">(Obrigatória)</small>
                                @else
                                    <label>{{$i . ')'}}</label>
                                    <label>{{$question->name}}</label>
                                    <label>{{$question->description}}</label>
                                @endif
                                <div class="form-group" >
                                    @if($question->type == 3)
                                        @if($question->required == 1)
                                            <textarea class="form-control" id="{{$question->id}}" name="{{$question->id}}" rows="3" required>{{$question->id}}</textarea>
                                            <input name="{{'type_' . $question->id}}" type="hidden" value=3>
                                        @else
                                            <textarea class="form-control" id="{{$question->id}}" name="{{$question->id}}" rows="3">{{$question->id}}</textarea>
                                            <input name="{{'type_' . $question->id}}" type="hidden" value=3>
                                        @endif
                                    @elseif($question->type == 1)
                                        @foreach($oqfs as $oqf)
                                            @foreach($options as $option)
                                                @if(($option->id == $oqf->option_id) && ($question->id == $oqf->question_id))
                                                    @if($question->required == 1)
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" id="{{$question->id . '-' . $option->id}}" type="radio" name="{{$question->id}}" value="{{$option->id}}" required>
                                                            <label class="custom-control-label" for="{{$question->id . '-' . $option->id}}">
                                                                {{$option->name}} {{$question->id . '-' . $option->id}}
                                                            </label>
                                                        </div>
                                                    @else
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" id="{{$question->id . '-' . $option->id}}" type="radio" name="{{$question->id}}" value="{{$option->id}}" >
                                                            <label class="custom-control-label" for="{{$question->id . '-' . $option->id}}">
                                                                {{$option->name}} {{$question->id . '-' . $option->id}}
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                        <input name="{{'type_' . $question->id}}" type="hidden" value=1>
                                    @else
                                        @foreach($oqfs as $oqf)
                                            @foreach($options as $option)
                                                @if(($option->id == $oqf->option_id) && ($question->id == $oqf->question_id))
                                                    @if($question->required == 1)
                                                        <div class="custom-control custom-checkbox" >
                                                            <input class="custom-control-input" type="checkbox" id="{{$option->id}}"  name="{{$question->id . '-' . $option->id}}" value="{{$option->id}}" >
                                                            <label class="custom-control-label" for="{{$option->id}}" >
                                                                {{$option->name}} {{$question->id . '-' . $option->id}}
                                                            </label>
                                                        </div>
                                                    @else
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="checkbox" id="{{$option->id}}"  name="{{$question->id . '-' . $option->id}}" value="{{$option->id}}" >
                                                            <label class="custom-control-label" for="{{$option->id}}">
                                                                {{$option->name}} {{$question->id . '-' . $option->id}}
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                        <input name="{{'type_' . $question->id}}" type="hidden" value=2>
                                    @endif
                                </div>
                            @php($i++)
                            @endforeach
                            <input name="form_id" type="hidden" value={{$form->id}}>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                            @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
