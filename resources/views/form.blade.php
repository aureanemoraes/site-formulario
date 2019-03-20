@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
            <div class="card-header">{{ ucwords($form->name) }}
            </div>
                <div class="card-body">
                    @if(isset($questions) && isset($options))
                    <form action="/form/save" method="POST">
                        @csrf
                        @php ($i = 0)
                        @php ($name = "op")
                        @foreach($questions as $question)
                            <div class="form-group">
                                <label>{{$question->name}}</label>
                            @foreach($options as $option)
                                @if($option->question_id == $question->id)
                                    <div class="form-check">
                                    <input class="form-check-input" type="radio" name="{{$name . $i}}" value="{{$option->id}}" required>
                                        <label class="form-check-label" for="{{$name . $i}}">
                                            {{$option->name}}
                                        </label>
                                    </div>
                                    @endif
                            @endforeach
                            </div>
                            @php ($i++)
                        @endforeach
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>

                    @else
                        <p>Não tem questões</p>

                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
