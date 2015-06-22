<?php

namespace website;

class View_Helper_Prueba extends \sowerphp\general\View_Helper_PDF
{

    public function Header()
    {
    }

    public function Footer()
    {
        $this->SetY($this->GetY());
        $this->SetFont('helvetica', 'B', 6);
        $this->Texto('Documento generado: '. date('Y-m-d H:i'));
        $this->Texto('Prueba generada mediante MiTeSt (mitest.cl)', null, null, 'R');
    }

    public function generar ($options)
    {
        // mezclar preguntas
        $optios['preguntas'] = shuffle($options['preguntas']);
        // mezclar alternativas de cada pregunta
        foreach($options['preguntas'] as &$pregunta) {
            shuffle($pregunta['alternativas']);
        }
        // agregar página al pdf
        $this->AddPage();
        // agregar título
        $this->SetFont($this->defaultOptions['font']['family'], 'B', 16);
        $this->Texto($options['titulo'].' '.$options['materia'].' ('.$options['version'].')', null, null, 'C');
        $this->Ln(6);
        $this->SetFont($this->defaultOptions['font']['family'], 'B', 10);
        $this->Texto($options['autor'].' - '.$options['organizacion'].' - '.$options['fecha'], null, null, 'C');
        $this->Ln(10);
        $this->Texto('Nombre: ___________________________________________________________________ Nota: _____________');
        $this->Ln(8);
        // agregar observaciones
        $this->SetFont($this->defaultOptions['font']['family'], '', 10);
        if((integer)$options['descuento']>0) {
            $this->Texto('¡Importante! cada '.$options['descuento'].' respuestas incorrectas se descontará una respuesta correcta.');
            $this->Ln(8);
        }
        // agregar preguntas
        $p = 1;
        $pauta = [];
        foreach ($options['preguntas'] as &$pregunta) {
            // mostrar pregunta si no hay imagen
            if (+$pregunta['imagen_size']==0) {
                $this->MultiTexto($p.'.- '.$pregunta['pregunta'].':');
                $this->Ln(1);
            }
            // agregar imagen si existe
            else {
                rewind($pregunta['imagen_data']);
                $imagen_data =  stream_get_contents($pregunta['imagen_data']);
                $image = imagecreatefromstring($imagen_data);
                if ($image!==false) {
                    $mm = round((imagesy($image)*25.4)/90);
                    // si la imagen se va a salir de la pagina pasar a la siguiente pagina
                    if(($this->GetY()+$mm) > 250) $this->AddPage();
                    // mostrar pregunta con la imagen
                    $this->MultiTexto($p.'.- '.$pregunta['pregunta'].':');
                    $this->Ln(1);
                    $this->setImageScale(1.5);
                    $this->Image('@'.$imagen_data, $this->GetX()+10, $this->GetY());
                    $this->SetY($this->GetY()+$mm*0.85);
                }
            }
            // Agregar alternativas
            $a = 'A';
            $respuestasCorrectas = [];
            foreach($pregunta['alternativas'] as &$alternativa) {
                // agregar a la pauta si es correcta
                if($alternativa['correcta']=='t') {
                    $respuestasCorrectas[] = $a;
                }
                // agregar al PDF
                $this->MultiTexto('          '.$a.') '.$alternativa['respuesta']);
                $this->Ln(1);
                ++$a;
            }
            // agregar a la pauta respuestas correctas
            $pauta[$p] = array(
                'correctas' => $respuestasCorrectas,
                'explicacion' => $pregunta['explicacion']
            );
            $this->Ln(1);
            // incrementar pregunta
            ++$p;
        }
        // agregar nueva hoja con la pauta
        $this->AddPage();
        $this->SetFont($this->defaultOptions['font']['family'], 'B', 16);
        $this->Texto('Pauta: '.$options['titulo'].' '.$options['materia'].' ('.$options['version'].')', null, null, 'C');
        $this->Ln(6);
        $this->SetFont($this->defaultOptions['font']['family'], 'B', 10);
        $this->Texto($options['autor'].' - '.$options['organizacion'].' - '.$options['fecha'], null, null, 'C');
        $this->Ln(10);
        // agregar respuestas de las preguntas
        $this->SetFont($this->defaultOptions['font']['family'], '', 10);
        foreach($pauta as $pregunta => &$respuesta) {
            $this->MultiTexto($pregunta.'.- '.implode(', ', $respuesta['correctas']).' : '.$respuesta['explicacion']);
            $this->Ln(3);
        }
    }

}
