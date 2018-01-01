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

namespace website;

/**
 * Modelo para trabajar con varias categorías
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2014-11-25
 */
class Model_Categorias extends \Model_Plural_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'categoria'; ///< Tabla del modelo

    public function getListByUser($id, $onlypublics = false)
    {
        return $this->db->getTable('
            SELECT id, categoria
            FROM categoria
            WHERE
                usuario = :id
                '.($onlypublics?'AND publica = true':'').'
            ORDER BY orden, categoria
        ', [':id'=>$id]);
    }

    public function getByUser($id)
    {
        return $this->db->getTable('
            SELECT c.id, c.categoria, c.publica, t.pruebas
            FROM categoria AS c LEFT JOIN (
                SELECT p.categoria, COUNT(*) AS pruebas
                FROM prueba AS p, categoria AS c
                WHERE p.categoria = c.id AND c.usuario = :id
                GROUP BY p.categoria
            ) AS t ON t.categoria = c.id
            WHERE c.usuario = :id
            ORDER BY c.orden, c.categoria
        ', [':id'=>$id]);
    }

}
