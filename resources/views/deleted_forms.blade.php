@extends('layouts.app')

@section('content')
@php($i=1)
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3 ">
            <div class="card ">
                <div class="card-body ">
                    <div class="list-group list-group-flush">
                        <a href="/home" class="list-group-item d-flex justify-content-between align-items-center">
                            Ativos
                            <span class="badge badge-success badge-pill">{{$countForms}}</span>
                        </a>
                        <a href="/deleted-forms" class="list-group-item d-flex justify-content-between align-items-center">
                            Finalizados
                            <span class="badge badge-danger badge-pill">{{$countTrashedForms}}</span>
                        </a>
                        <a href="/all-forms" class="list-group-item d-flex justify-content-between align-items-center">
                            Todos
                            <span class="badge badge-primary badge-pill">{{$countAllForms}}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
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
                            <th scope="col">Índice</th>
                            <th scope="col">Título</th>
                            <th scope="col">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                        @if(isset($trashedForms))
                            @foreach ($trashedForms as $form)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{ ucfirst($form->name) }}</td>
                                <td>
                                    <a href="{{'/show-graphic/' . $form->id}}" class="btn btn-sm btn-dark">Gráficos</a>
                                    <a href="{{'/active-form/' . $form->id}}" class="btn btn-sm btn-success">Ativar</a>
                                </td>
                            </tr>
                            @php($i++)
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
