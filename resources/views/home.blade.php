@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Meus Formulários</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-hover">
                            <thead>
                              <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Título</th>
                                <th scope="col">Ações</th>
                              </tr>
                            </thead>

                            <tbody>
                            @if(isset($forms))
                                @foreach ($forms as $form)
                                <tr>
                                    <td>{{$form->id}}</td>
                                    <td>{{ ucfirst($form->name) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{'/show-form/' . $form->id}}" class="btn btn-sm btn-info">Ver</a>
                                            <a href="{{'/edit-form/' . $form->id}}" class="btn btn-sm btn-warning">Editar</a>
                                            <a href="{{'/show-graphic/' . $form->id}}" class="btn btn-sm btn-dark">Gráficos</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <h5>Nenhum formulário adicionado.</h5>
                            @endif

                            </tbody>
                          </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
