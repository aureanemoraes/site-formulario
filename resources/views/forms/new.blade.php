@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Novo Formulário</div>
                <div class="card-body">
                    <form action="new-form" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Título</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Descrição</label>
                            <input type="text" class="form-control" name="description" id="description">
                        </div>

                        <div class="form-group">
                            <label for="duration">Duração</label>
                            <input type="text" class="form-control" name="duration" id="duration" aria-describedby="durationHelp" required>
                            <small id="durationHelp" class="form-text text-muted">Exemplo: 22-02-2019 16:00</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Criar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

