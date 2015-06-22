<h1>Resultados de la prueba resuelta</h1>
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
// resultados
new \sowerphp\general\View_Helper_Table(array(
    array('Correctas', 'Porcentaje', 'Nota'),
    array($correctas, $porcentaje, $nota),
));
