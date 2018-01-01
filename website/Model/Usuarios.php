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
 * Modelo para trabajar con datos de varios usuarios
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2014-11-08
 */
class Model_Usuarios extends \Model_Plural_App
{

    public function conCategoriasPruebasPublicas()
    {
        return $this->db->getTable('
            SELECT u.usuario, u.nombre, c.categorias, p.pruebas
            FROM
                usuario AS u,
                (
                    SELECT usuario, COUNT(*) AS categorias
                    FROM categoria
                    WHERE publica = true
                    GROUP BY usuario
                ) AS c,
                (
                    SELECT c.usuario, SUM(p.pruebas) AS pruebas
                    FROM
                        categoria AS c,
                        (
                            SELECT categoria, COUNT(*) AS pruebas
                            FROM prueba
                            WHERE publica = true
                            GROUP BY categoria
                        ) AS p
                    WHERE p.categoria = c.id
                    GROUP BY c.usuario
                ) AS p
            WHERE
                u.activo = true
                AND c.usuario = u.id
                AND p.usuario = u.id
            ORDER BY u.usuario
        ');
    }

}
