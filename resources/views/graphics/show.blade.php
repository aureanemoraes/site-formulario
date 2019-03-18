

@foreach ( $question as $q)
<div id="chart-div"></div>
<?= Lava::render('PieChart', $q->name, 'chart-div') ?>

@endforeach
<h1>$piechart</h1>
                        {{ var_dump($piechart) }}
