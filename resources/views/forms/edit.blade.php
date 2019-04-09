@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Novo Formulário</div>
                <div class="card-body">
                    @if(isset($form))
                        <form action="{{ '/edit-form/' . $form->id }} " method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Título</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{$form->name}}" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Descrição</label>
                            <input type="text" class="form-control" name="description" id="description" value="{{$form->description}}">
                            </div>

                            <input name="_method" type="hidden" value="PUT">

                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                    @else
                        <h5>Este formulário não existe.</h5>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

