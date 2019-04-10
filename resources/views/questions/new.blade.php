@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if(isset($id))
                <div class="card-header">Nova Questão</div>
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
                        <div class="form-check">
                            <input name="required" class="form-check-input" type="checkbox" id="required" value="true">
                            <label class="form-check-label" for="required">Esta questão é obrigatória.</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Criar</button>
                    </form>
                </div>
                @else
                    <h5>Esta ação não pode ser realizada.</h5>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

