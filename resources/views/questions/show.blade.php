@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$question->name}}</div>
                    <div class="jumbutron">
                        <div id="chart-div"></div>
                        <?= Lava::render('PieChart', $question->name, 'chart-div') ?>
                    </div>
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
