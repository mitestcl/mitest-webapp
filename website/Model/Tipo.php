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
 * Clase para mapear la tabla tipo de la base de datos
 * Comentario de la tabla: Tabla para los tipos de preguntas que pueden existir
 * Esta clase permite trabajar sobre un registro de la tabla tipo
 * @author SowerPHP Code Generator
 * @version 2014-11-09 00:46:42
 */
class Model_Tipo extends \Model_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'tipo'; ///< Tabla del modelo

    // Atributos de la clase (columnas en la base de datos)
    public $id; ///< Identificador del tipo: integer(32) NOT NULL DEFAULT 'nextval('tipo_id_seq'::regclass)' AUTO PK
    public $tipo; ///< Nombre del tipo (ej: fácil, normal o difícil): character varying(10) NOT NULL DEFAULT ''
    public $peso; ///< Indica dificultad (menor número, más fácil la pregunta): smallint(16) NOT NULL DEFAULT '0'
    public $porcentaje; ///< Porcentaje por defecto utilizado para seleccionar preguntas cuando se hace al azar: smallint(16) NOT NULL DEFAULT '0'

    // Información de las columnas de la tabla en la base de datos
    public static $columnsInfo = array(
        'id' => array(
            'name'      => 'Id',
            'comment'   => 'Identificador del tipo',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => false,
            'default'   => 'nextval(\'tipo_id_seq\'::regclass)',
            'auto'      => true,
            'pk'        => true,
            'fk'        => null
        ),
        'tipo' => array(
            'name'      => 'Tipo',
            'comment'   => 'Nombre del tipo (ej: fácil, normal o difícil)',
            'type'      => 'character varying',
            'length'    => 10,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'peso' => array(
            'name'      => 'Peso',
            'comment'   => 'Indica dificultad (menor número, más fácil la pregunta)',
            'type'      => 'smallint',
            'length'    => 16,
            'null'      => false,
            'default'   => '0',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'porcentaje' => array(
            'name'      => 'Porcentaje',
            'comment'   => 'Porcentaje por defecto utilizado para seleccionar preguntas cuando se hace al azar',
            'type'      => 'smallint',
            'length'    => 16,
            'null'      => false,
            'default'   => '0',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),

    );

    // Comentario de la tabla en la base de datos
    public static $tableComment = 'Tabla para los tipos de preguntas que pueden existir';

    public static $fkNamespace = array(); ///< Namespaces que utiliza esta clase

}
