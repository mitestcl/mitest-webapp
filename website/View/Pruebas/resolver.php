<h1>Resolver prueba en línea</h1>

<script type="text/javascript">
var preguntasTotales = <?php echo $total; ?>;
var preguntaActual = 0;
$(function(){
    $('input[type="submit"]').css('display', 'none');
    siguientePregunta();
});
</script>

<?php

// generar cabecera de la prueba
new \sowerphp\general\View_Helper_Table(array(
    array('Categoría', 'Prueba', 'Autor', 'Generada', 'Creada', 'Modificada'),
    array(
        '<a href="'.$_base.'/u/'.$usuario.'#'.$categoria_url.'-'.$categoria_id.'">'.$categoria.'</a>',
        '<a href="'.$_base.'/p/'.$id.'">'.$prueba.'</a>',
        '<a href="'.$_base.'/u/'.$usuario.'">'.$autor.'</a>',
        $generada,
        $creada,
        $modificada
    )
));

// formulario para preguntas
$form = new \sowerphp\general\View_Helper_Form('inline');
echo $form->begin($_base.'/pruebas/resultado/'.$id);

// generar preguntas
echo '<ol class="preguntas">';
$n = 1;
foreach($preguntas as &$pregunta) {
    // mostrar pregunta
    echo '<li id="pregunta',$n,'" style="display:none">';
    echo '<strong>Pregunta ',$n,'</strong>:<br /><br />';
    echo $pregunta->pregunta; //,' [',$pregunta->getTipo()->tipo,']';
    // mostrar imagen
    if(!empty($pregunta->imagen_name)) {
        echo '<br /><img src="',$_base,'/preguntas/imagen/',$pregunta->id,'" alt="',$pregunta->imagen_name,'" class="round4" style="max-width:100%;margin:1em 0" />';
    }
    // mostrar cantidad de preguntas que se deben seleccionar
    echo '<br />Seleccionar ',count($pregunta->answersCorrect()),' alternativa(s):';
    // mostrar alternativas
    echo '<ol class="alternativas">';
    foreach($pregunta->respuestas as &$answer) {
        echo '<li>';
        echo $form->input(array('type'=>'checkbox', 'name'=>'pregunta'.$pregunta->id.'[]', 'label'=>$answer->respuesta, 'value'=>$answer->id));
        echo '</li>';
    }
    echo '</ol>';
    // mostrar enlace para siguiente pregunta
    echo '<a href="javascript:siguientePregunta()" accesskey="n">Siguiente pregunta &gt;&gt;</a>';
    // terminar pregunta
    echo '</li>';
    // incrementar contador
    ++$n;
}
echo '</ol>';

// fin del formulario para preguntas
echo $form->end('Enviar respuestas de la prueba');
