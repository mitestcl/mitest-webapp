<h1>Categorías &raquo; Editar &raquo; <em><?php echo $Categoria->categoria; ?></em></h1>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin([
    'onsubmit'=>'Form.check()'
]);
echo $f->input([
    'name'=>'categoria',
    'label'=>'Categoría',
    'check'=>'notempty',
    'value'=>$Categoria->categoria
]);
echo $f->input([
    'type'=>'checkbox',
    'name'=>'publica',
    'label'=>'Pública',
    'checked'=>$Categoria->publica
]);
echo $f->end('Guardar cambios');
