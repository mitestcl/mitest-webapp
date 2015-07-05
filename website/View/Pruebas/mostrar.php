<h1><?php echo $prueba; ?></h1>

<?php
new \sowerphp\general\View_Helper_Table([
    ['Categoría', 'Autor', 'Creada', 'Modificada', 'Preguntas públicas'],
    [
        '<a href="'.$_base.'/u/'.$usuario.'#'.$categoria_url.'-'.$categoria_id.'">'.$categoria.'</a>',
        '<a href="'.$_base.'/u/'.$usuario.'">'.$autor.'</a>',
        $creada,
        $modificada,
        $publicas
    ]
]);
?>
<div class="center" style="padding:50px">
    <a href="<?=$_base?>/r/<?=$id?>" title="Resolver en línea"><img src="<?=$_base?>/img/icons/128x128/actions/resolver.png" alt="" /></a>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="<?=$_base?>/d/<?=$id?>" title="Descargar"><img src="<?=$_base?>/img/icons/128x128/actions/download.png" alt="" /></a>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="<?=$_base?>/d/<?=$id?>" title="Descargar"><img src="<?=$_base?>/exportar/qrcode/<?=base64_encode($_url.'/d/'.$id)?>" alt="" style="width:128px;height:128px" /></a>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="https://telegram.me/MiTeStBot?start=r:<?=$id?>" title="Resolver en Telegram"><img src="<?=$_base?>/img/icons/128x128/actions/telegram.png" alt="" /></a>
</div>

<h2>Pregunta de ejemplo</h2>
<p><?php echo $Pregunta->pregunta; ?><br />Seleccionar <?php echo count($Pregunta->answersCorrect()); ?> alternativa(s):</p>
<ol class="alternativas">
<?php
if(!empty($Pregunta->imagen_name)) {
    echo '<br /><img src="',$_base,'/preguntas/imagen/',$Pregunta->id,'" alt="',$Pregunta->imagen_name,'" class="round4" style="max-width:100%"/>';
}
foreach($Pregunta->respuestas as &$respuesta) {
    echo '<li>',$respuesta->respuesta,'</li>';
}
?>
</ol>
<p>¿Quieres resolver todas las preguntas disponibles? <a href="<?php echo $_base; ?>/r/<?php echo $id; ?>">¡click aquí!</a>.</p>

<h2>Estadísticas</h2>
<div class="center">
    <img src="<?php echo $_base; ?>/pruebas/grafico/privadas_publicas/<?php echo $id; ?>" alt="" />
    <img src="<?php echo $_base; ?>/pruebas/grafico/por_tipo/<?php echo $id; ?>" alt="" />
</div>

<h2>Comentarios</h2>
<script type="text/javascript">
    //<![CDATA[
    document.write('<div class="fb-comments" data-href="https://mitest.cl/p/<?php echo $id; ?>" data-width="100%"></div>');
    //]]>
</script>
