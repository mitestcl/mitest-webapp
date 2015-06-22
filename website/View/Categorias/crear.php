<h1>Categorías &raquo; Crear</h1>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin([
    'onsubmit'=>'Form.check()'
]);
echo $f->input([
    'name'=>'categoria',
    'label'=>'Categoría',
    'check'=>'notempty',
    'attr'=>'maxlength="50"',
]);
echo $f->input([
    'type'=>'checkbox',
    'name'=>'publica',
    'label'=>'Pública',
    'checked'=>true
]);
echo $f->end('Crear categoría');
