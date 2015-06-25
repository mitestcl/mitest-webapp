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
    'checked'=>true,
    'help'=>'Si la categoría es pública se mostrarán todas las pruebas públicas que le pertenecen. Si es privada no se mostrará ninguna prueba y la categoría no estará disponible a los usuarios.',
]);
echo $f->end('Crear categoría');
