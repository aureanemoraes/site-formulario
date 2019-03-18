@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Nova Questão</div>
                    <div class="jumbutron">
                        <div id="chart-div"></div>
                        <?= Lava::render('PieChart', $question->name, 'chart-div') ?>
                        <h1>$piechart</h1>
                        {{ var_dump($piechart) }}
                    </div>
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
