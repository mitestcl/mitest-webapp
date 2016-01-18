<h1>Pruebas &raquo; Listado del usuario <em><?=$usuario?></em></h1>
<?php
foreach($pruebas as &$prueba) {
    $prueba[] =
        '<a href="bajar/'.$prueba['id'].'" title="Bajar"><span class="fa fa-toggle-down btn btn-default"></span></a> '.
        '<a href="subir/'.$prueba['id'].'" title="Subir"><span class="fa fa-toggle-up btn btn-default"></span></a> '.
        '<a href="editar/'.$prueba['id'].'" title="Editar"><span class="fa fa-edit btn btn-default"></span></a> '.
        '<a href="eliminar/'.$prueba['id'].'" title="Eliminar" onclick="return eliminar(\''.$prueba['categoria'].'\', \''.$prueba['prueba'].'\')"><span class="fa fa-remove btn btn-default"></span></a>'
    ;
    unset($prueba['id']);
}
array_unshift($pruebas, array('Categoría', 'Prueba', 'Acciones'));
echo '<a href="crear" title="Nuevo"><span class="fa fa-plus btn btn-default"> Crear nueva prueba</span></a>';
new \sowerphp\general\View_Helper_Table($pruebas);
