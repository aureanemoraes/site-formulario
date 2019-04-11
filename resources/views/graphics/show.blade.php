@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3>Relatório</h3>
                    <p>Total: {{$form->amount}}</p>
                    @if(isset($questions))
                        @php ($i = 0)
                        @php ($name = "chart-div")
                        @foreach($questions as $q)
                        <div id="{{  $name . $i}}"></div>
                            @php ($fullname = $name . $i)
                            <?= Lava::render('PieChart', $q->name, $fullname) ?>
                            @php ($i++ )
                        @endforeach
                    @else
                        <h5>Este gráfico não existe.</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
