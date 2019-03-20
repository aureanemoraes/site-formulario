
@if(isset($question))
    @php ($i = 0)
    @php ($name = "chart-div")
    @foreach($question as $q)
    <div id="{{  $name . $i}}"></div>
        @php ($fullname = $name . $i)
        <?= Lava::render('PieChart', $q->name, $fullname) ?>
        @php ($i++ )
    @endforeach
@else
    <h5>Este gráfico não existe.</h5>
@endif
