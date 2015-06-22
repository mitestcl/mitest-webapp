<h1>Usuarios</h1>
<p>A continuación se listan los usuarios que tienen al menos una categoría y prueba pública.</p>
<?php
foreach($usuarios as &$usuario) {
    $usuario['usuario'] = '<a href="'.$_base.'/u/'.$usuario['usuario'].'">'.$usuario['usuario'].'</a>';
}
array_unshift($usuarios, array('Usuario', 'Nombre', 'Categorías públicas', 'Pruebas públicas'));
new \sowerphp\general\View_Helper_Table($usuarios);
