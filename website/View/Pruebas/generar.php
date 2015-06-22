<h1>Pruebas &raquo; Generar</h1>
<p>Aquí se consideran todas las preguntas y pruebas, públicas y privadas.</p>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin([
    'action'=>$_base.'/pruebas/generar_pdf',
    'onsubmit'=>'validarGeneracionPrueba()']);
echo $f->input([
    'name'=>'materia',
    'label'=>'Materia',
    'check'=>'notempty'
]);
echo $f->input([
    'name'=>'titulo',
    'label'=>'Título',
    'check'=>'notempty'
]);
echo $f->input([
    'name'=>'organizacion',
    'label'=>'Organización',
    'check'=>'notempty'
]);
echo $f->input([
    'name'=>'fecha',
    'label'=>'Fecha',
    'value'=>strtolower(date('j M Y')),
    'check'=>'notempty'
]);
echo $f->input([
    'name'=>'pruebas',
    'label'=>'Cantidad de pruebas',
    'value'=>1,
    'check'=>['notempty', 'integer'],
    'help'=>'Se generarán X versiones de la misma prueba, mezclando las preguntas y las alternativas'
]);
echo $f->input([
    'name'=>'preguntas',
    'label'=>'Total de preguntas',
    'value'=>24,
    'check'=>['notempty', 'integer']
]);
echo $f->input([
    'name'=>'descuento',
    'label'=>'Descuento por malas',
    'value'=>0,
    'check'=>['notempty', 'integer'],
    'help'=>'Cada X malas se descontará una pregunta buena'
]);
foreach($tipos as &$tipo) {
    echo $f->input([
        'name'=>'tipo_'.$tipo['id'],
        'label'=>'% de '.$tipo['tipo'],
        'value'=>$tipo['porcentaje'],
        'check'=>['notempty', 'integer']
    ]);
}
echo $f->input([
    'type' => 'tablecheck',
    'name' => 'prueba_id',
    'label' => 'Pruebas',
    'titles' => ['ID', 'Categoría', 'Prueba', 'Preguntas'],
    'table' => $pruebas,
]);
echo $f->end('Generar PDF');
