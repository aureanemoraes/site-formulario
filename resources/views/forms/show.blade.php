@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
            <div class="card-header">{{ ucwords($form->name) }}
                <div class="float-sm-right">
                    <a href="{{'/form/' . $form->id }}" class="btn btn-sm btn-success">Compartilhar Formulário</a>
                </div>
            </div>
                <div class="card-body">
                    @if(isset($questions) && isset($options))
                    <form action="" method="">
                        @foreach($questions as $question)
                            <p>{{$question->name}}</p>
                            @foreach($options as $option)
                                @if($option->question_id == $question->id)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="op" id="op" value="{{ $option->name }}">
                                        <label class="form-check-label" for="exampleRadios1">
                                            {{$option->name}}
                                        </label>
                                    </div>
                                    @endif
                            @endforeach
                        @endforeach
                    </form>

                    @else
                        <p>Não tem questões</p>

                    @endif
                    <a href="{{ '/new-question/' . $form->id }}" class="btn btn-primary">Adicionar questão</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
