<h1>Usuarios</h1>
<p>A continuación se listan los usuarios que tienen al menos una categoría y prueba pública.</p>
<?php
foreach($usuarios as &$usuario) {
    $usuario['usuario'] =
        '<a href="https://telegram.me/MiTeStBot?start=u:'.$usuario['usuario'].'" title="Abrir usuario en Telegram"><img src="'.$_base.'/img/icons/16x16/actions/telegram.png" alt="telegram" /></a>'.
        ' <a href="'.$_base.'/u/'.$usuario['usuario'].'" title="Abrir página del usuario">'.$usuario['usuario'].'</a>'
    ;
}
array_unshift($usuarios, array('Usuario', 'Nombre', 'Categorías públicas', 'Pruebas públicas'));
new \sowerphp\general\View_Helper_Table($usuarios);
