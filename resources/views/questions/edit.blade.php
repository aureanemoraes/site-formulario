@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Editar</div>
                <div class="card-body">
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

                        @if($question->type == 3)
                        @else
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
                                <input type="text" class="form-control" name="options" id="options" aria-describedby="optionsHelp" value="{{$op}}" required>
                                <small id="optionsHelp" class="form-text text-muted">Opções devem ser separadas por vírgula. (Ex.: Morango, Maçã, Banana)</small>
                            </div>
                        @endif
                        {{-- Mudar tipo da questão --}}
                        <div class="form-group">
                            <label for="type">Tipo</label>
                            <select id="type" class="form-control">
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
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="required" value=1>
                            <label class="form-check-label" for="required">Esta questão é obrigatória.</label>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Criar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

