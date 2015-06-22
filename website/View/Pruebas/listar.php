<h1>Pruebas &raquo; Listado del usuario <em><?=$usuario?></em></h1>
<?php
foreach($pruebas as &$prueba) {
    $prueba[] =
        '<a href="bajar/'.$prueba['id'].'" title="Bajar"><img src="'.$_base.'/img/icons/16x16/actions/down.png" alt="" /></a> '.
        '<a href="subir/'.$prueba['id'].'" title="Subir"><img src="'.$_base.'/img/icons/16x16/actions/up.png" alt="" /></a> '.
        '<a href="editar/'.$prueba['id'].'" title="Editar"><img src="'.$_base.'/img/icons/16x16/actions/edit.png" alt="" /></a> '.
        '<a href="eliminar/'.$prueba['id'].'" title="Eliminar" onclick="return eliminar(\''.$prueba['categoria'].'\', \''.$prueba['prueba'].'\')"><img src="'.$_base.'/img/icons/16x16/actions/delete.png" alt="" /></a>'
    ;
    unset($prueba['id']);
}
array_unshift($pruebas, array('Categoría', 'Prueba', 'Acciones'));
echo '<a href="crear" title="Nuevo"><img src="'.$_base.'/img/icons/16x16/actions/new.png" alt="" /></a>';
new \sowerphp\general\View_Helper_Table($pruebas);
