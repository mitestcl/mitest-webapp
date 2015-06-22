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
 * Controlador para las pruebas
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 * @version 2014-11-25
 */
class Controller_Pruebas extends \Controller_App
{

    private $allowedImageTypes = [IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF];

    public function beforeFilter()
    {
        $this->Auth->allow('descargar', 'resolver', 'resultado', 'mostrar', 'grafico');
        parent::beforeFilter();
    }

    /**
     * Acción para mostrar menú de acciones de administración de pruebas
     */
    public function index()
    {
        $nav = [
            '/generar' => [
                'name' => 'Generar prueba',
                'desc' => 'Generar prueba para descargar',
                'imag' => '/img/icons/48x48/generar.png',
            ],
            '/listar' => [
                'name' => 'Pruebas',
                'desc' => 'Crear, editar y/o eliminar pruebas',
                'imag' => '/img/icons/48x48/prueba.png',
            ],
            '/../categorias/listar' => [
                'name' => 'Categorías',
                'desc' => 'Crear, editar y/o eliminar categorías',
                'imag' => '/img/icons/48x48/categorias.png',
            ],
        ];
        $this->set([
            'title' => 'Pruebas',
            'nav' => $nav,
            'module' => 'pruebas',
        ]);
        $this->autoRender = false;
        $this->render('Module/index');
    }

    public function listar($page = 1, $orderby = null, $order = 'A')
    {
        $this->set([
            'usuario' => $this->Auth->User->usuario,
            'pruebas' => (new Model_Pruebas())->getByUser($this->Auth->User->id),
        ]);
    }

    public function crear()
    {
        // si no se ha enviado el formulario se mostrará
        if(!isset($_POST['submit'])) {
            $this->set(array(
                'categorias' => (new Model_Categorias())->getListByUser($this->Auth->User->id),
            ));
        }
        // si se envió el formulario se procesa
        else {
            $_POST['prueba'] = trim($_POST['prueba']);
            $_POST['descripcion'] = trim($_POST['descripcion']);
            if (!isset($_POST['prueba'][0]) or !isset($_POST['descripcion']) or !(int)$_POST['categoria']) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Nombre, descripción y categoría de la prueba no pueden estar en blanco', 'warning'
                );
                $this->redirect('/pruebas/crear');
            }
            // guardar prueba
            $Prueba = new Model_Prueba();
            $Prueba->prueba = $_POST['prueba'];
            $Prueba->descripcion = $_POST['descripcion'];
            $Prueba->categoria = $_POST['categoria'];
            $Prueba->publica = isset($_POST['publica']) ? 'true' : 'false';
            $Prueba->save();
            // agregar preguntas
            foreach($_POST['preguntas'] as &$preguntaId) {
                $_POST['pregunta'.$preguntaId] = trim($_POST['pregunta'.$preguntaId]);
                if (!isset($_POST['pregunta'.$preguntaId][0])) continue;
                // guardar pregunta
                $Pregunta = new Model_Pregunta();
                $Pregunta->pregunta = $_POST['pregunta'.$preguntaId];
                $Pregunta->prueba = $Prueba->id;
                $Pregunta->tipo = $_POST['tipo'.$preguntaId];
                $Pregunta->explicacion = trim($_POST['explicacion'.$preguntaId]);
                $Pregunta->publica = isset($_POST['publica'.$preguntaId]) ? 'true' : 'false';
                $Pregunta->activa = isset($_POST['activa'.$preguntaId]) ? 'true' : 'false';
                $Pregunta->save();
                // guardar imagen si es que existe
                if (!$_FILES['imagen'.$preguntaId]['error']) {
                    $Pregunta->saveImage($_FILES['imagen'.$preguntaId]);
                }
                // agregar respuestas
                foreach ($_POST['respuesta'.$preguntaId] as &$respuesta) {
                    $respuesta = trim($respuesta);
                    if (!isset($respuesta[0])) continue;
                    // determinar si la respuesta es correcta o incorrecta
                    $correcta = 'false';
                    if ($respuesta[0]=='*') {
                        $correcta = 'true';
                        $respuesta = substr($respuesta, 1);
                    }
                    // guardar respuesta
                    $Respuesta = new Model_Respuesta();
                    $Respuesta->respuesta = $respuesta;
                    $Respuesta->pregunta = $Pregunta->id;
                    $Respuesta->correcta = $correcta;
                    $Respuesta->save();
                }
            }
            // mensaje y redireccionar
            \sowerphp\core\Model_Datasource_Session::message(
               'Prueba creada', 'ok'
            );
            $this->redirect('/pruebas/listar');
        }
    }

    /**
     * Controlador para editar un registro de tipo Prueba
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-11-25
     */
    public function editar($id)
    {
        $Prueba = new Model_Prueba($id, false, false, false);
        // si el registro que se quiere editar y no existe error
        if (!$Prueba->exists()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La prueba solicitada no existe, no se puede editar',
                'error'
            );
            $this->redirect('/pruebas/listar');
        }
        // si no es el dueño de la prueba error
        if ($Prueba->getCategoria()->usuario!=$this->Auth->User->id) {
            \sowerphp\core\Model_Datasource_Session::message(
                'No es el autor de la prueba, no se puede editar',
                'error'
            );
            $this->redirect('/pruebas/listar');
        }
        // si no se ha enviado el formulario se mostrará
        if (!isset($_POST['submit'])) {
            $this->set(array(
                'Prueba' => $Prueba,
                'categorias' => (new Model_Categorias())->getListByUser($this->Auth->User->id),
            ));
        }
        // si se envió el formulario se procesa
        else {
            $_POST['prueba'] = trim($_POST['prueba']);
            $_POST['descripcion'] = trim($_POST['descripcion']);
            if (!isset($_POST['prueba'][0]) or !isset($_POST['descripcion']) or !(int)$_POST['categoria']) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Nombre, descripción y categoría de la prueba no pueden estar en blanco', 'warning'
                );
                $this->redirect('/pruebas/editar/'.$Prueba->id);
            }
            // guardar datos generales de la prueba
            $Prueba->prueba = $_POST['prueba'];
            $Prueba->descripcion = $_POST['descripcion'];
            $Prueba->categoria = $_POST['categoria'];
            $Prueba->publica = isset($_POST['publica']) ? 'true' : 'false';
            $Prueba->modificada = date('Y-m-d H:i:s');
            $Prueba->save();
            // limpiar preguntas, dejando solo las que han sido pasadas
            if (is_array($_POST['preguntasIds']))
                $Prueba->dejarPreguntas($_POST['preguntasIds']);
            // guardar preguntas
            foreach ($_POST['preguntas'] as &$preguntaId) {
                $_POST['pregunta'.$preguntaId] = trim($_POST['pregunta'.$preguntaId]);
                if (!isset($_POST['pregunta'.$preguntaId][0])) continue;
                // guardar pregunta
                $Pregunta = new Model_Pregunta();
                $Pregunta->id = !empty($_POST['id'.$preguntaId]) ? $_POST['id'.$preguntaId] : null;
                $Pregunta->get();
                $Pregunta->pregunta = $_POST['pregunta'.$preguntaId];
                $Pregunta->prueba = $Prueba->id;
                $Pregunta->tipo = $_POST['tipo'.$preguntaId];
                $Pregunta->explicacion = trim($_POST['explicacion'.$preguntaId]);
                $Pregunta->publica = isset($_POST['publica'.$preguntaId]) ? 'true' : 'false';
                $Pregunta->activa = isset($_POST['activa'.$preguntaId]) ? 'true' : 'false';
                $Pregunta->save();
                // guardar imagen si es que existe
                if (!$_FILES['imagen'.$preguntaId]['error']) {
                    $detectedType = exif_imagetype($_FILES['imagen'.$preguntaId]['tmp_name']);
                    if (!in_array($detectedType, $this->allowedImageTypes)) {
                        \sowerphp\core\Model_Datasource_Session::message(
                            'Imagen debe ser PNG, JPG o GIF', 'warning'
                        );
                        $this->redirect('/pruebas/editar/'.$Prueba->id);
                    }
                    $Pregunta->saveImage($_FILES['imagen'.$preguntaId]);
                }
                // limpiar respuestas, dejando solo las que han sido pasadas
                if (is_array($_POST['respuestaId'.$preguntaId]))
                    $Pregunta->dejarRespuestas($_POST['respuestaId'.$preguntaId]);
                // guardar respuestas
                foreach ($_POST['respuesta'.$preguntaId] as $key => &$respuesta) {
                    $respuesta = trim($respuesta);
                    if (!isset($respuesta[0])) continue;
                    // determinar si la respuesta es correcta o incorrecta
                    $correcta = 'false';
                    if ($respuesta[0]=='*') {
                        $correcta = 'true';
                        $respuesta = substr($respuesta, 1);
                    }
                    // guardar respuesta
                    $Respuesta = new Model_Respuesta();
                    $Respuesta->id = !empty($_POST['respuestaId'.$preguntaId][$key]) ? $_POST['respuestaId'.$preguntaId][$key] : null;
                    $Respuesta->get();
                    $Respuesta->respuesta = $respuesta;
                    $Respuesta->pregunta = $Pregunta->id;
                    $Respuesta->correcta = $correcta;
                    $Respuesta->save();
                }
            }
            // mensaje y redireccionar
            \sowerphp\core\Model_Datasource_Session::message(
                'Prueba editada',
                'ok'
            );
            $this->redirect('/pruebas/listar');
        }
    }

    public function eliminar($id)
    {
        $Prueba = new Model_Prueba($id, false, false);
        // si el registro que se quiere eliminar y no existe error
        if (!$Prueba->exists()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La prueba solicitada no existe, no se puede eliminar',
                'error'
            );
            $this->redirect('/pruebas/listar');
        }
        // si no es el dueño de la prueba error
        if ($Prueba->getCategoria()->usuario!=$this->Auth->User->id) {
            \sowerphp\core\Model_Datasource_Session::message(
                'No es el autor de la prueba, no se puede eliminar',
                'error'
            );
            $this->redirect('/pruebas/listar');
        }
        // eliminar prueba
        $Prueba->delete();
        \sowerphp\core\Model_Datasource_Session::message(
            'Prueba eliminada', 'ok'
        );
        $this->redirect('/pruebas/listar');
    }

    public function subir($id)
    {
        $Prueba = new Model_Prueba($id);
        // si el registro que se quiere editar y no existe error
        if (!$Prueba->exists()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La prueba solicitada no existe, no se puede editar',
                'error'
            );
            $this->redirect('/pruebas/listar');
        }
        // si no es el dueño de la prueba error
        if ($Prueba->getCategoria()->usuario!=$this->Auth->User->id) {
            \sowerphp\core\Model_Datasource_Session::message(
                'No es el autor de la prueba, no se puede editar',
                'error'
            );
            $this->redirect('/pruebas/listar');
        }
        // solo se procesa si el orden es mayor a 1
        if($Prueba->orden>1) {
            $Prueba->intercambiarOrden($Prueba->orden-1);
        }
        // redireccionar
        $this->redirect('/pruebas/listar');
    }

    public function bajar($id)
    {
        $Prueba = new Model_Prueba($id);
        // si el registro que se quiere editar y no existe error
        if (!$Prueba->exists()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La prueba solicitada no existe, no se puede editar',
                'error'
            );
            $this->redirect('/pruebas/listar');
        }
        // si no es el dueño de la prueba error
        if ($Prueba->getCategoria()->usuario!=$this->Auth->User->id) {
            \sowerphp\core\Model_Datasource_Session::message(
                'No es el autor de la prueba, no se puede editar',
                'error'
            );
            $this->redirect('/pruebas/listar');
        }
        // solo se procesa si el orden es menor que el maximo existente
        $Pruebas = new Model_Pruebas();
        $Pruebas->setWhereStatement(['categoria = :categoria'], [':categoria'=>$Prueba->categoria]);
        if($Prueba->orden<$Pruebas->getMax('orden')) {
            $Prueba->intercambiarOrden($Prueba->orden+1);
        }
        // redireccionar
        $this->redirect('/pruebas/listar');
    }

    public function generar()
    {
        $categorias = (new Model_Categorias())->getListByUser($this->Auth->User->id);
        $Pruebas = new Model_Pruebas();
        $pruebas = [];
        foreach($categorias as &$categoria) {
            $aux = $Pruebas->getByCategoria($categoria['id']);
            foreach($aux as &$prueba) {
                $pruebas[] = [
                    $prueba['id'],
                    $categoria['categoria'],
                    $prueba['prueba'],
                    $prueba['preguntas'],
                ];
            }
        }
        $this->set([
            'tipos' => (new Model_Tipos())->getList(true),
            'pruebas' => $pruebas,
        ]);
    }

    public function generar_pdf()
    {
        // si no se viene por POST redireccionar
        if (!isset($_POST['submit'])) {
            \sowerphp\core\Model_Datasource_Session::message(
                'No puede acceder directamente a la página '.$this->request->request,
                'warning'
            );
            $this->redirect('/pruebas/generar');
        }
        // determinar cantidad de cada tipo
        $tipos_porcentaje = [];
        $tipos = (new Model_Tipos())->getList();
        foreach($tipos as &$t) {
            $tipos_porcentaje[$t['id']] = !empty($_POST['tipo_'.$t['id']]) ? $_POST['tipo_'.$t['id']] : 0;
        }
        // obtener preguntas
        $preguntas = (new Model_Pruebas())->getPreguntas(
            $_POST['prueba_id'],
            $this->Auth->User->id,
            $tipos_porcentaje,
            $_POST['preguntas']
        );
        // asignar variables para la vista
        $this->set([
            'pruebas_cantidad' => $_POST['pruebas'],
            'materia' => $_POST['materia'],
            'titulo' => $_POST['titulo'],
            'autor' => $this->Auth->User->nombre,
            'organizacion' => $_POST['organizacion'],
            'fecha' => $_POST['fecha'],
            'descuento' => $_POST['descuento'],
            'preguntas' => $preguntas,
        ]);

    }

    /**
     * Acción para descargar una prueba
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-11-26
     */
    public function descargar($id, $format = null)
    {
        // si el formato es null se lee desde la configuración
        if (!$format) $format = \sowerphp\core\Configure::read('test.format');
        $Prueba = new Model_Prueba($id); // do the magic
        // si la prueba no existe se da un mensaje de error
        if (!$Prueba->exists(true)) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La prueba que ha solicidado no existe, no especificó una o bien es una prueba privada (o de categoría privada)',
                'error'
            );
            $this->redirect('/');
        }
        // variables para nombre de archivos
        $name = $Prueba->getCategoria()->getUsuario()->usuario.'_'.
                \sowerphp\core\Utility_String::normalize($Prueba->getCategoria()->categoria).'_'.
                $Prueba->orden.'-'.\sowerphp\core\Utility_String::normalize($Prueba->prueba);
        $filename = TMP.DIRECTORY_SEPARATOR.'mitest-'.\sowerphp\core\Utility_String::random(20).'.zip';
        // crear archivo zip
        $zip = new \ZipArchive();
        if (($res=$zip->open($filename, \ZIPARCHIVE::CREATE))!==true) {
            $msg = '';
            foreach (['ER_OPEN', 'ER_EXISTS', 'ER_INVAL', 'ER_MEMORY'] as $e) {
                if ($res == constant('\ZIPARCHIVE::'.$e)) {
                    $msg = $e;
                    break;
                }
            }
            throw new \sowerphp\core\Exception([
                'error' => 'No se pudo crear el archivo ZIP ('.$msg.')'
            ]);
        }
        // agregar directorio que contiene los archivos
        $zip->addEmptyDir($name);
        // agregar archivo con la prueba
        if($format=='mt' || $format=='all')
            $zip->addFromString($name.DIRECTORY_SEPARATOR.$name.'.mt', $Prueba->getMT());
        if($format=='json' || $format=='all')
            $zip->addFromString($name.DIRECTORY_SEPARATOR.$name.'.json', $Prueba->getJSON());
        if($format=='xml' || $format=='all')
            $zip->addFromString($name.DIRECTORY_SEPARATOR.$name.'.xml', $Prueba->getXML());
        if(!in_array($format, array('mt', 'json', 'xml', 'all'))) {
            throw new MiErrorException(array(
                'error' => 'Formato de prueba "'.$format.'" no soportado'
            ));
        }
        // agregar imagenes al archivo
        $zip->addEmptyDir($name.DIRECTORY_SEPARATOR.'img');
        $imagenes = $Prueba->getImagenes();
        foreach($imagenes as &$imagen) {
            $imagen['data'] = stream_get_contents($imagen['data']);
            $zip->addFromString($name.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$imagen['name'], $imagen['data']);
        }
        // cerrar zip
        $zip->close();
        // enviar archivo
        $this->response->sendFile(
            $filename,
            array(
                'name' => $name.'.zip',
                'disposition' => 'attachement',
                'exit' => false,
            )
        );
        // eliminar archivo temporal
        unlink($filename);
        // terminar script
        exit(0);
    }

    /**
     * Acción para mostrar formulario para resolver prueba en línea
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-11-09
     */
    public function resolver($prueba)
    {
        $Prueba = new Model_Prueba($prueba); // do the magic
        // si la prueba no existe se da un mensaje de error
        if (!$Prueba->exists(true)) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La prueba que ha solicidado no existe, no especificó una o bien es una prueba privada (o de categoría privada)',
                'error'
            );
            $this->redirect('/');
        }
        // setear variables
        $this->set(array(
            'id'=>$Prueba->id,
            'categoria'=>$Prueba->getCategoria()->categoria,
            'prueba'=>$Prueba->prueba,
            'autor'=>$Prueba->autor,
            'generada'=>$Prueba->generada,
            'modificada'=>$Prueba->modificada,
            'creada'=>$Prueba->creada,
            'preguntas'=>$Prueba->preguntas,
            'total'=>$Prueba->questions(),
            'usuario'=>$Prueba->getCategoria()->getUsuario()->usuario,
            'categoria_id'=>$Prueba->categoria,
            'categoria_url'=>\sowerphp\core\Utility_String::normalize($Prueba->getCategoria()->categoria),
            'header_title'=>'Resolver '.$Prueba->prueba.' ('.$Prueba->getCategoria()->categoria.') by '.$Prueba->getCategoria()->getUsuario()->usuario,
        ));
    }

    /**
     * Acción para mostrar los resultados de la prueba enviada en línea
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-11-09
     */
    public function resultado($prueba)
    {
        $Prueba = new Model_Prueba($prueba);
        // si la prueba no existe error
        if (!$Prueba->exists(true)) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La prueba que ha solicidado no existe, no especificó una o bien es una prueba privada (o de categoría privada)',
                'error'
            );
            $this->redirect('/');
        }
        // si no se viene de un formulario error
        if (!isset($_POST['submit'])) {
            \sowerphp\core\Model_Datasource_Session::message(
                'No puede acceder directamente a la página de resultados',
                'error'
            );
            $this->redirect('/p/'.$prueba);
        }
        // si es un formulario se procesa
        else {
            // armar arreglo con las respuestas
            $respuestas = array();
            foreach($_POST as $key => &$value) {
                if(substr($key, 0, 8) == 'pregunta') {
                    $respuestas[substr($key, 8)] = $value;
                    sort($respuestas[substr($key, 8)]);
                }
            }
            // procesar respuestas
            $correctas = $this->_check($Prueba->preguntas, $respuestas);
            // setear variables para la vista
            $total = $Prueba->questions();
            $porcentaje = ($correctas/$total)*100;
            $this->set(array(
                'id'=>$Prueba->id,
                'categoria'=>$Prueba->getCategoria()->categoria,
                'prueba'=>$Prueba->prueba,
                'autor'=>$Prueba->autor,
                'generada'=>$Prueba->generada,
                'modificada'=>$Prueba->modificada,
                'creada'=>$Prueba->creada,
                'correctas' => $correctas.' / '.$total,
                'porcentaje' => number_format($porcentaje).' / 100',
                'nota' => number_format(6*($porcentaje/100)+1, 1).' / 7.0',
                'usuario'=>$Prueba->getCategoria()->getUsuario()->usuario,
                'categoria_id'=>$Prueba->categoria,
                'categoria_url'=>\sowerphp\core\Utility_String::normalize($Prueba->getCategoria()->categoria),
            ));
        }
    }

    private function _check($preguntas, $respuestas)
    {
        $correctas = 0;
        // para cada pregunta de la prueba
        foreach($preguntas as &$pregunta) {
            // verificar solo si se respondió la pregunta
            if(isset($respuestas[$pregunta->id]) && $pregunta->answersCorrect()==$respuestas[$pregunta->id]) {
                ++$correctas;
            }
        }
        return $correctas;
    }

    public function mostrar($prueba)
    {
        $Prueba = new Model_Prueba($prueba); // do the magic
        // si la prueba no existe se da un mensaje de error
        if (!$Prueba->exists(true)) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La prueba que ha solicidado no existe, no especificó una o bien es una prueba privada (o de categoría privada)',
                'error'
            );
            $this->redirect('/');
        }
        // total de preguntas
        $publicas = $Prueba->questions();
        // definir pregunta random
        $Pregunta = $Prueba->preguntas[rand(0,$publicas-1)];
        // setear variables
        $this->set(array(
            'id'=>$Prueba->id,
            'categoria'=>$Prueba->getCategoria()->categoria,
            'prueba'=>$Prueba->prueba,
            'autor'=>$Prueba->autor,
            'generada'=>$Prueba->generada,
            'modificada'=>$Prueba->modificada,
            'creada'=>$Prueba->creada,
            'Pregunta'=>$Pregunta,
            'publicas'=>$publicas,
            'usuario'=>$Prueba->getCategoria()->getUsuario()->usuario,
            'categoria_id'=>$Prueba->categoria,
            'categoria_url'=>\sowerphp\core\Utility_String::normalize($Prueba->getCategoria()->categoria),
            'header_title'=>$Prueba->prueba.' ('.$Prueba->getCategoria()->categoria.') by '.$Prueba->getCategoria()->getUsuario()->usuario,
        ));
    }

    public function grafico($tipo, $prueba)
    {
        $Prueba = new Model_Prueba($prueba); // do the magic
        // si la prueba no existe se da un mensaje de error
        if(!$Prueba->exists(true)) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La prueba que ha solicidado no existe, no especificó una o bien es una prueba privada (o de categoría privada)',
                'error'
            );
            $this->redirect('/');
        }
        // datos
        if ($tipo == 'privadas_publicas') {
            $title = 'Preguntas privadas y públicas';
            $publicas = $Prueba->questions();
            $data = ['Privadas'=>$Prueba->totalPreguntas()-$publicas, 'Públicas'=>$publicas];
        } else if ($tipo == 'por_tipo') {
            $title = 'Preguntas por tipo';
            $data = $Prueba->preguntasPorTipo();
        }
        // generar gráfico
        $chart = new \sowerphp\general\View_Helper_Chart();
        $chart->pie($title, $data, ['width'=>450, 'height'=>250]);
    }

}
