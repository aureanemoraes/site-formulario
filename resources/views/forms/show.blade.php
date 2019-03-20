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
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Perguntas</th>
                                <th scope="col">Respostas</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($questions) && isset($options) && isset($oqfs))
                                @foreach($questions as $question)
                                <tr scope="row">
                                        <td>{{$question->name}}</td>
                                        <td>
                                    @if($question->type == 3)
                                        <p>Discursiva.</p>
                                    @else
                                    @foreach($oqfs as $oqf)
                                        <ul class="list-group">
                                        @foreach($options as $option)
                                            @if(($option->id == $oqf->option_id) && ($question->id == $oqf->question_id))
                                                        <li class="list-group-item list-group-item-action">{{$option->name}}</li>
                                                @endif
                                        @endforeach
                                        </ul>
                                    @endforeach
                                    @endif
                                </td>
                                <td>
                                        <div class="btn-group">
                                        <a href="{{'/edit-form/' . $form->id}}" class="btn btn-sm btn-warning">Editar</a>
                                        <a href="{{'/show-graphic/' . $form->id}}" class="btn btn-sm btn-dark">Gráficos</a>
                                        </div>
                                </td>
                                </tr>
                                @endforeach
                        </tbody>

                    @else
                        <p>Este formulário não possui questões adicionadas.</p>
                    @endif
                    </table>
                </div>
<div id="accordion">
<div class="card">
    <div  id="headingOne">
    <h5 class="mb-0">
        <button class="btn btn-primary btn-lg btn-block" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
        Resposta Única
        </button>
    </h5>
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="card-body">

        <form action="/new-question/save" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Pergunta</label>
                <textarea class="form-control" name="name" id="name" required></textarea>
            </div>

            <div class="form-group">
                <label for="description">Descrição</label>
                <input type="text" class="form-control" name="description" id="description">
            </div>

            <div class="form-group">
                <label for="options">Opcões</label>
                <input type="text" class="form-control" name="options" id="options" aria-describedby="optionsHelp" required>
                <small id="optionsHelp" class="form-text text-muted">Opções devem ser separadas por vírgula. (Ex.: Morango, Maçã, Banana)</small>
            </div>

            <input type="hidden" id="form_id" name="form_id" value="{{ $form->id }}">
            <input type="hidden" id="type" name="type" value=1>

            <button type="submit" class="btn btn-primary">Criar</button>
        </form>

    </div>
    </div>
</div>
<div class="card">
    <div id="headingTwo">
    <h5 class="mb-0">
        <button class="btn btn-info btn-lg btn-block" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        Múltiplas Respostas
        </button>
    </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
    <div class="card-body">
        <form action="/new-question/save" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Pergunta</label>
                <textarea class="form-control" name="name" id="name" required></textarea>
            </div>

            <div class="form-group">
                <label for="description">Descrição</label>
                <input type="text" class="form-control" name="description" id="description">
            </div>

            <div class="form-group">
                <label for="options">Opcões</label>
                <input type="text" class="form-control" name="options" id="options" aria-describedby="optionsHelp" required>
                <small id="optionsHelp" class="form-text text-muted">Opções devem ser separadas por vírgula. (Ex.: Morango, Maçã, Banana)</small>
            </div>

            <input type="hidden" id="form_id" name="form_id" value="{{ $form->id }}">

            <input type="hidden" id="type" name="type" value=2>

            <button type="submit" class="btn btn-primary">Criar</button>
        </form>
    </div>
    </div>
</div>
<div class="card">
    <div id="headingThree">
    <h5 class="mb-0">
        <button class="btn btn-secondary btn-lg btn-block" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        Subjetiva
        </button>
    </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
    <div class="card-body">
        <form action="/new-question/save" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Pergunta</label>
                <textarea class="form-control" name="name" id="name" required></textarea>
            </div>

            <div class="form-group">
                <label for="description">Descrição</label>
                <input type="text" class="form-control" name="description" id="description">
            </div>

            <input type="hidden" id="form_id" name="form_id" value="{{ $form->id }}">

            <input type="hidden" id="type" name="type" value=3>

            <button type="submit" class="btn btn-primary">Criar</button>
        </form>
    </div>
    </div>
</div>
</div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
