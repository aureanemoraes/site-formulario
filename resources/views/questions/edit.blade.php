@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Editar
                    <div class="float-sm-right">
                            <a href="#" class="btn btn-sm btn-secondary">Voltar</a>
                    </div>

                </div>
                <div class="card-body">
                    @if ( session()->has('data') == '1' )
                        <div class="alert alert-danger" role="alert">
                            Campo <strong>opções</strong> vazio. Por favor, para utilizar questões discursivas mude o tipo da sua questão para <strong>DISCURSIVA</strong>.
                        </div>
                    @elseif (session()->has('data_2') == '2')
                        <div class="alert alert-danger" role="alert">
                            Campo <strong>Opções</strong> preenchido. Por favor, para utilizar opções mude o tipo da sua questão para <strong>Resposta Única</strong>
                            ou <strong>Múltiplas Respostas</strong>.
                        </div>
                    @elseif(session()->has('data_3') == '3')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Opções iguais!</strong> Você adicionou uma questão com duas ou mais opções idênticas. Por favor, edite suas opções. {{session('data.id')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <form action={{'/edit-question/' . $question->id}} method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Pergunta</label>
                            <textarea class="form-control" name="name" id="name" required>{{ $question->name }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="description">Descrição</label>
                            <input type="text" class="form-control" name="description" value="" id="description">
                        </div>
                        @php($op = "")
                        @php($first = true)
                        @foreach($options as $option)
                            @if($first == true)
                                @php($op = $option->name)
                                @php($first = false)
                            @else
                                @php($op = $op . ', '. $option->name)
                            @endif
                        @endforeach
                        <div class="form-group">
                            <label for="options">Opcões</label>
                            <div class="alert alert-warning" role="alert">
                                Se esta for uma questão <strong>discursiva</strong>. Deixar este campo em branco!
                            </div>
                            <input type="text" class="form-control" name="options" id="options" aria-describedby="optionsHelp" value="{{$op}}">
                            <small id="optionsHelp" class="form-text text-muted">Opções devem ser separadas por vírgula. (Ex.: Morango, Maçã, Banana)</small>

                        </div>

                        {{-- Mudar tipo da questão --}}
                        <div class="form-group">
                            <label for="type">Tipo</label>
                            <select name="type" id="type" class="form-control">
                                @if($question->type == 1)
                                    <option value=1 selected>Resposta Única</option>
                                    <option value=2>Multiplas Respostas</option>
                                    <option value=3>Discursiva</option>
                                    @elseif($question->type == 2)
                                        <option value=1>Resposta Única</option>
                                        <option value=2 selected >Multiplas Respostas</option>
                                        <option value=3>Discursiva</option>
                                    @else
                                        <option value=1>Resposta Única</option>
                                        <option value=2>Multiplas Respostas</option>
                                        <option value=3 selected>Discursiva</option>
                                @endif
                            </select>
                        </div>
                        <input name="_method" type="hidden" value="PUT">
                        {{--- Verificando se a questão é obrigatória --}}
                        @if($question->required == 0)
                            <div class="form-check">
                                <input name="required" class="form-check-input" type="checkbox" id="required" value=1 >
                                <label class="form-check-label" for="required">Esta questão é obrigatória.</label>
                            </div>
                        @else
                            <div class="form-check">
                                <input name="required" class="form-check-input" type="checkbox" id="required" value=1 checked>
                                <label class="form-check-label" for="required">Esta questão é obrigatória.</label>
                            </div>
                        @endif

                        <br>
                        <button type="submit" class="btn btn-primary">Salvar</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

