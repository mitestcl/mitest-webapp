<h1>Categorías &raquo; Listado del usuario <em><?=$usuario?></em></h1>
<?php
foreach($categorias as &$categoria) {
    $categoria['publica'] = $categoria['publica'] ? 'Si' : 'No';
    $categoria[] =
        '<a href="bajar/'.$categoria['id'].'" title="Bajar"><span class="far fa-caret-square-down btn btn-default"></span></a> '.
        '<a href="subir/'.$categoria['id'].'" title="Subir"><span class="far fa-caret-square-up btn btn-default"></span></a> '.
        '<a href="editar/'.$categoria['id'].'" title="Editar"><span class="fa fa-edit btn btn-default"></span></a> '.
        '<a href="eliminar/'.$categoria['id'].'" title="Eliminar" onclick="return eliminar(\'Categoría\', \''.$categoria['categoria'].'\')"><span class="fas fa-times btn btn-default"></span></a>'
    ;
    unset($categoria['id']);
}
array_unshift($categorias, array('Categoría', 'Pública', 'Pruebas', 'Acciones'));
echo '<a href="crear" title="Nuevo"><span class="fa fa-plus btn btn-default"> Crear nueva categoría</span></a>';
new \sowerphp\general\View_Helper_Table($categorias);
