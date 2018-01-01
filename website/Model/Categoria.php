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
 * Clase para mapear la tabla categoria de la base de datos
 * Comentario de la tabla: Tabla para categorías de las pruebas
 * Esta clase permite trabajar sobre un registro de la tabla categoria
 * @author SowerPHP Code Generator
 * @version 2014-11-09 00:29:19
 */
class Model_Categoria extends \Model_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'categoria'; ///< Tabla del modelo

    // Atributos de la clase (columnas en la base de datos)
    public $id; ///< Identificador de la categoría: integer(32) NOT NULL DEFAULT 'nextval('categoria_id_seq'::regclass)' AUTO PK
    public $categoria; ///< Nombre de la categoría: character varying(50) NOT NULL DEFAULT ''
    public $usuario; ///< Dueño de la categoría: integer(32) NOT NULL DEFAULT '' FK:usuario.id
    public $madre; ///< Categoría madre de esta categoría: integer(32) NULL DEFAULT '' FK:categoria.id
    public $publica; ///< Indica si es visible para todos o solo para su dueño: boolean() NOT NULL DEFAULT 'true'
    public $orden; ///< Orden en que debe ser listada: smallint(16) NOT NULL DEFAULT '0'

    // Información de las columnas de la tabla en la base de datos
    public static $columnsInfo = array(
        'id' => array(
            'name'      => 'Id',
            'comment'   => 'Identificador de la categoría',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => false,
            'default'   => 'nextval(\'categoria_id_seq\'::regclass)',
            'auto'      => true,
            'pk'        => true,
            'fk'        => null
        ),
        'categoria' => array(
            'name'      => 'Categoria',
            'comment'   => 'Nombre de la categoría',
            'type'      => 'character varying',
            'length'    => 50,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'usuario' => array(
            'name'      => 'Usuario',
            'comment'   => 'Dueño de la categoría',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => array('table' => 'usuario', 'column' => 'id')
        ),
        'madre' => array(
            'name'      => 'Madre',
            'comment'   => 'Categoría madre de esta categoría',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => array('table' => 'categoria', 'column' => 'id')
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
        'orden' => array(
            'name'      => 'Orden',
            'comment'   => 'Orden en que debe ser listada',
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
    public static $tableComment = 'Tabla para categorías de las pruebas';

    public static $fkNamespace = array(
        'Model_Usuario' => 'sowerphp\app\Sistema\Usuarios',
        'Model_Categoria' => 'website'
    ); ///< Namespaces que utiliza esta clase

    public function save()
    {
        if (!$this->exists()) {
            $this->orden = $this->db->getValue('
                SELECT CASE WHEN MAX(orden)>0 THEN MAX(orden)+1 ELSE 1 END
                FROM categoria
                WHERE usuario = :usuario
            ', [':usuario'=>$this->usuario]);
        }
        parent::save();
    }

    public function intercambiarOrden($orden)
    {
        $this->db->query('
            UPDATE categoria
            SET orden = :orden1
            WHERE usuario = :usuario AND orden = :orden2
        ', [':usuario'=>$this->usuario, ':orden1'=>$this->orden, ':orden2'=>$orden]);
        $this->db->query('
            UPDATE categoria
            SET orden = :orden
            WHERE id = :id
        ', [':id'=>$this->id, ':orden'=>$orden]);
    }

    public function pruebasCount()
    {
        return $this->db->getValue(
            'SELECT COUNT(*) FROM prueba WHERE categoria = :categoria',
            [':categoria'=>$this->id]
        );
    }

}
