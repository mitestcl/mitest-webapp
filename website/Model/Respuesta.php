<?php

/**
 * SowerPHP: Minimalist Framework for PHP
 * Copyright (C) SowerPHP (http://sowerphp.org)
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

// namespace del modelo
namespace website;

/**
 * Clase para mapear la tabla respuesta de la base de datos
 * Comentario de la tabla: Tabla para respuestas de las preguntas
 * Esta clase permite trabajar sobre un registro de la tabla respuesta
 * @author SowerPHP Code Generator
 * @version 2014-11-09 00:40:43
 */
class Model_Respuesta extends \Model_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'respuesta'; ///< Tabla del modelo

    // Atributos de la clase (columnas en la base de datos)
    public $id; ///< Identificador de la respuesta: integer(32) NOT NULL DEFAULT 'nextval('respuesta_id_seq'::regclass)' AUTO PK 
    public $respuesta; ///< Posible respuesta a la pregunta: text() NOT NULL DEFAULT '' 
    public $pregunta; ///< Pregunta a la que pertenece la respuesta: integer(32) NOT NULL DEFAULT '' FK:pregunta.id
    public $correcta; ///< Indica si la respuesta es correcta: boolean() NOT NULL DEFAULT 'false' 

    // Información de las columnas de la tabla en la base de datos
    public static $columnsInfo = array(
        'id' => array(
            'name'      => 'Id',
            'comment'   => 'Identificador de la respuesta',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => false,
            'default'   => 'nextval(\'respuesta_id_seq\'::regclass)',
            'auto'      => true,
            'pk'        => true,
            'fk'        => null
        ),
        'respuesta' => array(
            'name'      => 'Respuesta',
            'comment'   => 'Posible respuesta a la pregunta',
            'type'      => 'text',
            'length'    => null,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'pregunta' => array(
            'name'      => 'Pregunta',
            'comment'   => 'Pregunta a la que pertenece la respuesta',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => array('table' => 'pregunta', 'column' => 'id')
        ),
        'correcta' => array(
            'name'      => 'Correcta',
            'comment'   => 'Indica si la respuesta es correcta',
            'type'      => 'boolean',
            'length'    => null,
            'null'      => false,
            'default'   => 'false',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),

    );

    // Comentario de la tabla en la base de datos
    public static $tableComment = 'Tabla para respuestas de las preguntas';

    public static $fkNamespace = array(
        'Model_Pregunta' => 'website'
    ); ///< Namespaces que utiliza esta clase

}
