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
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#oneanswer">
                        Resposta Única
                </button>
                <!-- Modal -->
                <div class="modal fade" id="oneanswer" tabindex="-1" role="dialog" aria-labelledby="oneanswerTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="oneanswerTitle">Resposta Única</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
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

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Criar</button>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#multipleanswer">
                        Múltiplas Respostas
                </button>
                <!-- Modal -->
                <div class="modal fade" id="multipleanswer" tabindex="-1" role="dialog" aria-labelledby="multipleanswerTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="multipleanswerTitle">Resposta Única</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
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

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Criar</button>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#textanswer">
                        Discursiva
                </button>
                <!-- Modal -->
                <div class="modal fade" id="textanswer" tabindex="-1" role="dialog" aria-labelledby="textanswerTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="textanswerTitle">Discursiva</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
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

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Criar</button>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
