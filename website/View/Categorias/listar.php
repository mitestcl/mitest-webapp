<h1>Categorías &raquo; Listado del usuario <em><?=$usuario?></em></h1>
<?php
foreach($categorias as &$categoria) {
    $categoria['publica'] = $categoria['publica'] ? 'Si' : 'No';
    $categoria[] =
        '<a href="bajar/'.$categoria['id'].'" title="Bajar"><img src="'.$_base.'/img/icons/16x16/actions/down.png" alt="" /></a> '.
        '<a href="subir/'.$categoria['id'].'" title="Subir"><img src="'.$_base.'/img/icons/16x16/actions/up.png" alt="" /></a> '.
        '<a href="editar/'.$categoria['id'].'" title="Editar"><img src="'.$_base.'/img/icons/16x16/actions/edit.png" alt="" /></a> '.
        '<a href="eliminar/'.$categoria['id'].'" title="Eliminar" onclick="return eliminar(\'Categoría\', \''.$categoria['categoria'].'\')"><img src="'.$_base.'/img/icons/16x16/actions/delete.png" alt="" /></a>'
    ;
    unset($categoria['id']);
}
array_unshift($categorias, array('Categoría', 'Pública', 'Pruebas', 'Acciones'));
echo '<a href="crear" title="Nuevo"><img src="'.$_base.'/img/icons/16x16/actions/new.png" alt="" /></a>';
new \sowerphp\general\View_Helper_Table($categorias);
