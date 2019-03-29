@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ ucwords($form->name) }}</div>
                <div class="card-body">
                    <form action="/form/save" method="POST">
                        @csrf
                            @if(isset($questions) && isset($options) && isset($oqfs))
                            @foreach($questions as $question)
                                    <label>{{$question->name}}</label>
                                @if($question->type == 3)
                                    <div class="form-group">
                                        <textarea class="form-control" id="{{$question->id}}" name="{{$question->id}}" rows="3">{{$question->id}}</textarea>
                                    </div>
                                    <input name="{{'type_' . $question->id}}" type="hidden" value=3>
                                @else
                                    @foreach($oqfs as $oqf)
                                    <div class="form-check" required>
                                        @foreach($options as $option)
                                            @if(($option->id == $oqf->option_id) && ($question->id == $oqf->question_id))
                                                <input class="form-check-input" type="radio" name="{{$question->id}}" value="{{$option->id}}" >
                                                <label class="form-check-label" for="{{$question->id}}">
                                                    {{$option->name}} {{$question->id}}
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                    @endforeach
                                    <input name="{{'type_' . $question->id}}" type="hidden" value=1>
                                @endif
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
