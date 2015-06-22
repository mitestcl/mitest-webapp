<?php

/**
 * MiTeSt
 * Copyright (C) 2014 Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General GNU para obtener
 * una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/gpl.html>.
 */

namespace website;

/**
 * Controlador para preguntas
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 * @version 2014-11-09
 */
class Controller_Preguntas extends \Controller_App
{

    public function beforeFilter()
    {
        $this->Auth->allow('imagen');
        parent::beforeFilter();
    }

    /**
     * Acción para mostrar la imagen asociada a una pregunta
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-11-25
     */
    public function imagen($pregunta)
    {
        $Pregunta = new Model_Pregunta($pregunta);
        if (!$Pregunta->exists()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'Pregunta solicitada no existe', 'error'
            );
            $this->redirect('/');
        }
        if (!$Pregunta->publica) {
            \sowerphp\core\Model_Datasource_Session::message(
                'Pregunta solicitada no es pública', 'error'
            );
            $this->redirect('/');
        }
        if ($Pregunta->imagen_size==0) {
            \sowerphp\core\Model_Datasource_Session::message(
                'Pregunta solicitada no tiene imagen asociada', 'error'
            );
            $this->redirect('/');
        }
        $this->response->sendFile([
            'name' => $Pregunta->imagen_name,
            'type' => $Pregunta->imagen_type,
            'size' => $Pregunta->imagen_size,
            'data' => $Pregunta->imagen_data,
        ], ['cache'=>1]);
    }

}
