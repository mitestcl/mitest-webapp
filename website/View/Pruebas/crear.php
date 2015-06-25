<script type="text/javascript">
    window.onbeforeunload = confirmExit;
    var needToConfirmToExit = true;
    function confirmExit() {
        if(needToConfirmToExit)
            return 'Estás editando una prueba...';
    }
</script>
<script type="text/javascript"> var preguntaId = 1; </script>

<h1>Pruebas &raquo; Crear</h1>

<?php
array_unshift($categorias, ['', 'Seleccione una categoría']);
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin([
    'onsubmit'=>'validarEdicionPrueba()'
]);
echo $f->input([
    'type'=>'select',
    'name'=>'categoria',
    'label'=>'Categoría',
    'options'=>$categorias,
    'check'=>'notempty',
    'help'=>'Si la categoría no existe la puede crear <a href="'.$_base.'/categorias/crear">aquí</a>',
]);
echo $f->input([
    'name'=>'prueba',
    'label'=>'Prueba',
    'check'=>'notempty',
    'attr'=>'maxlength="100"',
    'help'=>'Nombre de la prueba dentro de la categoría. Se recomienda utilizar el tópico.',
    'placeholder'=>'Introducción a X',
]);
echo $f->input([
    'type'=>'textarea',
    'name'=>'descripcion',
    'label'=>'Descripción',
    'rows'=>5,
    'check'=>'notempty',
    'help'=>'Explicación de que contenidos serán abordados en esta prueba',
]);
echo $f->input([
    'type'=>'checkbox',
    'name'=>'publica',
    'label'=>'Pública',
    'checked'=>true,
    'help'=>'Indica si la prueba puede ser revisada por cualquier usuario. Útil sólo si la prueba contendrá preguntas públicas. A pesar que la prueba sea pública, aquellas preguntas marcadas como privadas (o no públicas) no serán visibles por los usuarios.',
]);
echo $f->input([
    'type'=>'div',
    'label'=>'Preguntas <a href="javascript:agregarPregunta()" title="Agregar una pregunta"><img src="'.$_base.'/img/icons/16x16/actions/add.png" alt="add" /></a>',
    'value'=>'<div id="preguntas"></div>'
]);
echo $f->end('Crear prueba');
?>
<script type="text/javascript"> agregarPregunta(); </script>
