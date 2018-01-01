<script type="text/javascript">
    window.onbeforeunload = confirmExit;
    var needToConfirmToExit = true;
    function confirmExit() {
        if(needToConfirmToExit)
            return 'Estás editando una prueba...';
    }
</script>
<script type="text/javascript"> var preguntaId = <?php echo count($Prueba->preguntas)+1; ?>; </script>

<h1>Pruebas &raquo; Editar &raquo; <em><?php echo $Prueba->prueba; ?></em></h1>

<?php

// agregar preguntas existentes con sus respuestas
$preguntaId = 1;
$preguntas = [];
foreach($Prueba->preguntas as &$Pregunta) {
    // crear respuestas para la pregunta
    $respuestas = [];
    foreach($Pregunta->respuestas as &$Respuesta) {
        $value = ($Respuesta->correcta=='t'?'*':'').$Respuesta->respuesta;
        $respuestas[] = <<<EOF
<div>
    <input type="hidden" name="respuestaId{$preguntaId}[]" value="{$Respuesta->id}" />
    <input type="text" name="respuesta{$preguntaId}[]" value="${value}" placeholder="Alternativa" class="respuesta" />
    <a href="#" onclick="$(this).parent().remove(); return false" title="Eliminar" class="fright"><span class="fas fa-times btn btn-default"></span></a>
</div>
EOF;
    }
    $respuestas = implode("\n", $respuestas);
    // determinar si es está activa
    $activa = $Pregunta->activa ? ' checked="checked"' : '';
    // determinar tipo de la pregunta
    $tipo1 = $Pregunta->tipo==1 ? ' selected="selected"' : '';
    $tipo2 = $Pregunta->tipo==2 ? ' selected="selected"' : '';
    $tipo3 = $Pregunta->tipo==3 ? ' selected="selected"' : '';
    // determinar si es publica
    $publica = $Pregunta->publica=='t' ? ' checked="checked"' : '';
    // determinar si tiene imagen
    $imagen = $Pregunta->imagen_size ? '<img src="'.$_base.'/preguntas/imagen/'.$Pregunta->id.'" alt="imagen_'.$Pregunta->id.'" class="imagen round4" />' : '';
    // agregar pregunta
    $preguntas[] = <<<EOF
<div>
    <input type="hidden" name="preguntas[]" value="{$preguntaId}" />
    <input type="hidden" name="preguntasIds[]" value="{$Pregunta->id}" />
    <input type="hidden" name="id{$preguntaId}" value="{$Pregunta->id}" />
    <div>
        <a href="#" onclick="$(this).parent().parent().remove(); return false" title="Eliminar" class="fright"><span class="fas fa-times btn btn-default"></span></a>
        Activa: <input type="checkbox" name="activa{$preguntaId}"{$activa} />
        Pública: <input type="checkbox" name="publica{$preguntaId}"{$publica} />
        Tipo: <select name="tipo{$preguntaId}" class="tipo">
            <option value="1"{$tipo1}>Fácil</option>
            <option value="2"{$tipo2}>Normal</option>
            <option value="3"{$tipo3}>Difícil</option>
        </select>
        Imagen: <input type="file" name="imagen{$preguntaId}" />
    </div>
    <div><textarea name="pregunta{$preguntaId}" placeholder="Pregunta" class="pregunta">{$Pregunta->pregunta}</textarea></div>
    {$imagen}
    <div class="respuestas">
        <a href="javascript:agregarRespuesta({$preguntaId})" title="Agregar alternativa">[+] Agregar alternativa</a>
        <div id="respuestas{$preguntaId}">${respuestas}</div>
        <span>Respuesta(s) correcta(s) inicia(n) con un *</span>
    </div>
    <div class="clear"><textarea name="explicacion{$preguntaId}" placeholder="Explicación" class="explicacion">{$Pregunta->explicacion}</textarea></div>
</div>
EOF;
    // aumentar contador de preguntas
    ++$preguntaId;
}
$preguntas = implode("\n", $preguntas);

// crear formulario
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin([
    'onsubmit'=>'validarEdicionPrueba()'
]);
echo $f->input([
    'type'=>'hidden',
    'name'=>'id',
    'value'=>$Prueba->id
]);
echo $f->input([
    'type'=>'select',
    'name'=>'categoria',
    'label'=>'Categoría',
    'options'=>$categorias,
    'value'=>$Prueba->categoria,
    'check'=>'notempty',
    'help'=>'Si la categoría no existe la puede crear <a href="'.$_base.'/categorias/crear">aquí</a>',
]);
echo $f->input([
    'name'=>'prueba',
    'label'=>'Prueba',
    'check'=>'notempty',
    'value'=>$Prueba->prueba,
    'attr'=>'maxlength="100"',
    'help'=>'Nombre de la prueba dentro de la categoría. Se recomienda utilizar el tópico.',
]);
echo $f->input([
    'type'=>'textarea',
    'name'=>'descripcion',
    'label'=>'Descripción',
    'rows'=>5,
    'check'=>'notempty',
    'value'=>$Prueba->descripcion,
    'help'=>'Explicación de que contenidos serán abordados en esta prueba',
]);
echo $f->input([
    'type'=>'checkbox',
    'name'=>'publica',
    'label'=>'Pública',
    'checked'=>$Prueba->publica,
    'help'=>'Indica si la prueba puede ser revisada por cualquier usuario. Útil sólo si la prueba contendrá preguntas públicas. A pesar que la prueba sea pública, aquellas preguntas marcadas como privadas (o no públicas) no serán visibles por los usuarios.',
]);
echo $f->input([
    'type'=>'div',
    'label'=>'Preguntas <a href="javascript:agregarPregunta()" title="Agregar una pregunta"><span class="fa fa-plus btn btn-default"></span></a>',
    'value'=>'<div id="preguntas">'.$preguntas.'</div>'
]);
echo $f->end('Guardar cambios');
