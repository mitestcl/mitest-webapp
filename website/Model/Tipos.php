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
 * Esta clase permite trabajar sobre un conjunto de registros de la tabla tipo
 * @author SowerPHP Code Generator
 * @version 2014-11-09 00:46:42
 */
class Model_Tipos extends \Model_Plural_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'tipo'; ///< Tabla del modelo

    public function getList($conPorcentaje = false)
    {
        if ($conPorcentaje) {
            return $this->db->getTable(
                'SELECT id, tipo, porcentaje FROM tipo ORDER BY peso'
            );
        } else {
            return $this->db->getTable(
                'SELECT id, tipo FROM tipo ORDER BY peso'
            );
        }
    }

}
