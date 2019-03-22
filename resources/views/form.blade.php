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
                        @php ($i = 0)
                        @php ($name = "op")
                            @if(isset($questions) && isset($options) && isset($oqfs))
                            @foreach($questions as $question)
                                    <label>{{$question->name}}</label>
                                @if($question->type == 3)
                                    <div class="form-group">
                                        <textarea class="form-control" id="{{$name . $i}}" name="{{$name . $i}}" rows="3">{{$name . $i}}</textarea>
                                    </div>
                                    <input name="{{'type' . $i }}" type="hidden" value=3>
                                @php ($i++)
                                @else
                                @foreach($oqfs as $oqf)
                                <div class="form-check" required>
                                    @foreach($options as $option)
                                        @if(($option->id == $oqf->option_id) && ($question->id == $oqf->question_id))
                                            <input class="form-check-input" type="radio" name="{{$name . $i}}" value="{{$option->id}}" >
                                            <label class="form-check-label" for="{{$name . $i}}">
                                                {{$option->name}} {{$name . $i}}
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                                <input name="{{'type' . $i }}" type="hidden" value=3>
                                @endforeach
                                @php ($i++)
                                @endif

                            @endforeach
                            <button type="submit" class="btn btn-primary">Enviar</button>
                            @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
