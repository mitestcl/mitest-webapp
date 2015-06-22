<?php

// crear objeto para pdf
$pdf = new View_Helper_Prueba('P','mm','Letter');

// generar pruebas
for ($i=0; $i<$pruebas_cantidad; ++$i) {
    $pdf->generar([
        'materia' => $materia,
        'titulo' => $titulo,
        'autor' => $autor,
        'organizacion' => $organizacion,
        'fecha' => $fecha,
        'descuento' => $descuento,
        'preguntas'=> $preguntas,
        'version' => strtoupper(\sowerphp\core\Utility_String::random(6)),
    ], $pdf);
}

// enviar pdf al navegador
$filename = \sowerphp\core\Utility_String::normalize($materia.' '.$titulo).'.pdf';
$pdf->Output($filename, 'D');
exit(0);
