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
                            @foreach ($forms as $form)
                              <tr>
                                <td>{{$form->id}}</td>
                                <td>{{ ucfirst($form->name) }}</td>
                                <td>
                                    <a href="{{'/show-form/' . $form->id}}" class="btn btn-sm btn-info">Ver</a>
                                    <a href="{{'/show-form/' . $form->id}}" class="btn btn-sm btn-warning">Editar</a>
                                    <a href="{{'/show-form/' . $form->id}}" class="btn btn-sm btn-dark">Gráficos</a>
                                </td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
