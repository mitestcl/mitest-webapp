<?php

/**
 * MiTeSt
 * Copyright (C) SASCO SpA (https://sasco.cl)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */

// namespace del modelo
namespace website;

/**
 * Clase para mapear la tabla pregunta de la base de datos
 * Comentario de la tabla: Tabla para las preguntas de las pruebas
 * Esta clase permite trabajar sobre un registro de la tabla pregunta
 * @author SowerPHP Code Generator
 * @version 2014-11-09 00:45:53
 */
class Model_Pregunta extends \Model_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'pregunta'; ///< Tabla del modelo

    // Atributos de la clase (columnas en la base de datos)
    public $id; ///< Identificador de la pregunta: integer(32) NOT NULL DEFAULT 'nextval('pregunta_id_seq'::regclass)' AUTO PK
    public $pregunta; ///< Pregunta: text() NOT NULL DEFAULT ''
    public $prueba; ///< Prueba a la que pertenece la pregunta: integer(32) NOT NULL DEFAULT '' FK:prueba.id
    public $tipo; ///< Tipo de pregunta (por ejemplo indicará si es fácil, normal o difícil): integer(32) NOT NULL DEFAULT '' FK:tipo.id
    public $imagen_name; ///< Nombre del archivo de la imagen: character varying(50) NULL DEFAULT ''
    public $imagen_type; ///< Mimetype de la imagen: character varying(10) NULL DEFAULT ''
    public $imagen_size; ///< Tamaño de la imagen: integer(32) NULL DEFAULT ''
    public $imagen_data; ///< Imagen: bytea() NULL DEFAULT ''
    public $explicacion; ///< El porque la o las respuestas correctas son las correctas: text() NULL DEFAULT ''
    public $publica; ///< Indica si es visible para todos o solo para su dueño: boolean() NOT NULL DEFAULT 'true'
    public $activa; ///< boolean() NOT NULL DEFAULT 'true'

    // Información de las columnas de la tabla en la base de datos
    public static $columnsInfo = array(
        'id' => array(
            'name'      => 'Id',
            'comment'   => 'Identificador de la pregunta',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => false,
            'default'   => 'nextval(\'pregunta_id_seq\'::regclass)',
            'auto'      => true,
            'pk'        => true,
            'fk'        => null
        ),
        'pregunta' => array(
            'name'      => 'Pregunta',
            'comment'   => 'Pregunta',
            'type'      => 'text',
            'length'    => null,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'prueba' => array(
            'name'      => 'Prueba',
            'comment'   => 'Prueba a la que pertenece la pregunta',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => array('table' => 'prueba', 'column' => 'id')
        ),
        'tipo' => array(
            'name'      => 'Tipo',
            'comment'   => 'Tipo de pregunta (por ejemplo indicará si es fácil, normal o difícil)',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => array('table' => 'tipo', 'column' => 'id')
        ),
        'imagen_name' => array(
            'name'      => 'Imagen Name',
            'comment'   => 'Nombre del archivo de la imagen',
            'type'      => 'character varying',
            'length'    => 50,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'imagen_type' => array(
            'name'      => 'Imagen Type',
            'comment'   => 'Mimetype de la imagen',
            'type'      => 'character varying',
            'length'    => 10,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'imagen_size' => array(
            'name'      => 'Imagen Size',
            'comment'   => 'Tamaño de la imagen',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'imagen_data' => array(
            'name'      => 'Imagen Data',
            'comment'   => 'Imagen',
            'type'      => 'bytea',
            'length'    => null,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'explicacion' => array(
            'name'      => 'Explicacion',
            'comment'   => 'El porque la o las respuestas correctas son las correctas',
            'type'      => 'text',
            'length'    => null,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'publica' => array(
            'name'      => 'Publica',
            'comment'   => 'Indica si es visible para todos o solo para su dueño',
            'type'      => 'boolean',
            'length'    => null,
            'null'      => false,
            'default'   => 'true',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'activa' => array(
            'name'      => 'Activa',
            'comment'   => '',
            'type'      => 'boolean',
            'length'    => null,
            'null'      => false,
            'default'   => 'true',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),

    );

    // Comentario de la tabla en la base de datos
    public static $tableComment = 'Tabla para las preguntas de las pruebas';

    public static $fkNamespace = array(
        'Model_Prueba' => 'website',
        'Model_Tipo' => 'website'
    ); ///< Namespaces que utiliza esta clase

    public $respuestas; ///< Listado de respuestas
    public $correctas = null; ///< Alternativas correctas para la pregunta

    /**
     * Método que guarda la pregunta.
     * Este método es necesario porque la imagen que se recupera desde la BD
     * es un recurso, enconces si se escribe directamente lo que se recupera
     * se estaría escribiendo un string del tipo "Resource id #X". Para evitar
     * esto si lo que se recupera es un recurso entonces se obtienen los datos
     * y se guarda como un LOB.
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2014-11-25
     */
    public function save()
    {
        if (is_resource($this->imagen_data)) {
            rewind($this->imagen_data);
            $this->imagen_data = [
                stream_get_contents($this->imagen_data),
                \PDO::PARAM_LOB
            ];
        }
        parent::save();
    }

    /**
     * Método que carga las respuestas asociadas a esta pregunta
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2014-11-08
     */
    public function loadAnswers($random = true)
    {
        $Respuestas = new Model_Respuestas();
        $Respuestas->setWhereStatement(['pregunta = :pregunta'], [':pregunta'=>$this->id]);
        if ($random) $Respuestas->setOrderByStatement('RANDOM()');
        else $Respuestas->setOrderByStatement('id');
        $this->respuestas = $Respuestas->getObjects();
    }

    /**
     * Método que entrega la cantidad de respuestas correctas que tiene esta pregunta
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2012-11-02
     */
    public function answersCorrect()
    {
        if ($this->correctas) return $this->correctas;
        else {
            $this->correctas = array();
            foreach($this->respuestas as &$answer) {
                if($answer->correcta=='t') $this->correctas[] = $answer->id;
            }
            sort($this->correctas);
            return $this->correctas;
        }
    }

    /**
     * Método para guardar la imagen de una pregunta
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2014-11-25
     */
    public function saveImage($file)
    {
        $this->db->query('
            UPDATE pregunta
            SET
                imagen_data = :data
                , imagen_name = :name
                , imagen_type = :type
                , imagen_size = :size
            WHERE id = :pregunta
        ', [
            ':pregunta' => $this->id,
            ':data' => [file_get_contents($file['tmp_name']), \PDO::PARAM_LOB],
            ':name' => $file['name'],
            ':type' => $file['type'],
            ':size' => $file['size']
        ]);
    }

    /**
     * Método que elimina las respuestas que no se hayan indicado
     * @param dejar Arreglo con las respuestas que se deben dejar
     */
    public function dejarRespuestas($dejar)
    {
        $dejar = array_map('intval', $dejar);
        $this->db->query('
            DELETE FROM respuesta
            WHERE
                pregunta = :pregunta
                AND id NOT IN ('.implode(', ', $dejar).')
        ', [':pregunta'=>$this->id]);
    }

    /**
     * Método que entrega el atributo imagen_data (como resource)
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-07-05
     */
    public function getImagenData()
    {
        if (!is_resource($this->imagen_data)) {
            $this->imagen_data = $this->db->getValue(
                'SELECT imagen_data FROM pregunta WHERE id = :id',
                [':id'=>$this->id]
            );
        }
        return $this->imagen_data;
    }

}
