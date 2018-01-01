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
 * Clase para mapear la tabla prueba de la base de datos
 * Comentario de la tabla: Tabla para pruebas de los usuarios
 * Esta clase permite trabajar sobre un conjunto de registros de la tabla prueba
 * @author SowerPHP Code Generator
 * @version 2014-07-24 18:11:39
 */
class Model_Pruebas extends \Model_Plural_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'prueba'; ///< Tabla del modelo

    public function getByUser($id)
    {
        return $this->db->getTable('
            SELECT p.id, c.categoria, p.prueba
            FROM prueba AS p, categoria AS c
            WHERE
                usuario = :id
                AND p.categoria = c.id
            ORDER BY c.orden, c.categoria, p.orden, p.prueba
        ', [':id'=>$id]);
    }

    public function getByCategoria($id, $onlypublics = false)
    {
        return $this->db->getTable('
            SELECT
                p.id,
                p.prueba,
                p.descripcion,
                to_char(p.creada, \'DD mon YYYY, HH24:MI\') AS creada,
                to_char(p.modificada, \'DD mon YYYY, HH24:MI\') AS modificada,
                COUNT(*) AS preguntas
            FROM
                prueba AS p, pregunta
            WHERE
                pregunta.prueba = p.id
                AND p.categoria = :id
                '.($onlypublics?'AND p.publica = true AND pregunta.publica = true':'').'
            GROUP BY p.id, p.prueba, p.descripcion, creada, modificada
            ORDER BY p.orden, p.creada
        ', [':id'=>$id]);
    }

    public function getListByCategoria($id, $onlypublics = false)
    {
        return $this->db->getTable('
            SELECT id, prueba
            FROM prueba
            WHERE
                categoria = :id
                '.($onlypublics?'AND publica = true':'').'
            ORDER BY orden, creada
        ', [':id'=>$id]);
    }

    public function getPreguntas($pruebas, $usuario, $tipos_porcentaje, $preguntas_cantidad)
    {
        // obtener preguntas
        $preguntas = $this->db->getTable('
            SELECT
                pregunta.id,
                pregunta.pregunta,
                pregunta.tipo,
                pregunta.explicacion,
                pregunta.imagen_name,
                pregunta.imagen_type,
                pregunta.imagen_size,
                pregunta.imagen_data
            FROM pregunta, prueba, categoria
            WHERE
                pregunta.prueba = prueba.id
                AND prueba.categoria = categoria.id
                AND categoria.usuario = :usuario
                AND pregunta.prueba IN ('.implode(', ', array_map('intval', $pruebas)).')
                AND pregunta.activa = true
            ORDER BY random()
        ', [':usuario'=>$usuario]);
        // procesar preguntas por tipo
        $tiposAux = (new Model_Tipos())->getList();
        $tipos = [];
        $faltan = 0;
        $existentes = [];
        foreach($tiposAux as $key => &$tipoAux) {
            $existentes[$key] = count($this->preguntasTipo($preguntas, $tipoAux['id']));
        }
        asort($existentes);
        foreach(array_keys($existentes) as $i) {
            $tipoAux = $tiposAux[$i];
            // valores de porcentaje a usar y preguntas que se deben extraer
            $tipos[$tipoAux['id']]['porcentaje'] =  $tipos_porcentaje[$tipoAux['id']];
            $tipos[$tipoAux['id']]['necesitan'] = $preguntas_cantidad * $tipos[$tipoAux['id']]['porcentaje']/100 + $faltan;
            // agregar preguntas
            $tipos[$tipoAux['id']]['preguntas'] = $this->preguntasTipo($preguntas, $tipoAux['id']);
            // determinar valores para poder trabajar
            $tipos[$tipoAux['id']]['existen'] = count($tipos[$tipoAux['id']]['preguntas']);
            $tipos[$tipoAux['id']]['faltan'] = $tipos[$tipoAux['id']]['necesitan'] - $tipos[$tipoAux['id']]['existen'];
            if ($tipos[$tipoAux['id']]['faltan']<0)
                $tipos[$tipoAux['id']]['faltan'] = 0;
            $tipos[$tipoAux['id']]['sacar'] = min($tipos[$tipoAux['id']]['existen'], $tipos[$tipoAux['id']]['necesitan']);
            // guardar las preguntas que faltaran para ser usada en el siguiente tipo
            $faltan = $tipos[$tipoAux['id']]['faltan'];
            // recuperar al azar las preguntas que son necesarias solamente sacar
            if ($tipos[$tipoAux['id']]['sacar']) {
                $tipos[$tipoAux['id']]['seleccionadas'] = array_rand($tipos[$tipoAux['id']]['preguntas'], $tipos[$tipoAux['id']]['sacar']);
                if (!is_array($tipos[$tipoAux['id']]['seleccionadas']))
                    $tipos[$tipoAux['id']]['seleccionadas'] = array($tipos[$tipoAux['id']]['seleccionadas']);
            } else
                $tipos[$tipoAux['id']]['seleccionadas'] = array();
        }
        // obtener respuestas de las preguntas seleccionadas
        $preguntas = [];
        foreach($tipos as &$tipo) {
            foreach($tipo['seleccionadas'] as &$key) {
                // obtener pregunta
                $pregunta = $tipo['preguntas'][$key];
                // obtener alternativas de la pregunta
                $pregunta['alternativas'] = $this->db->getTable('
                    SELECT respuesta, correcta
                    FROM respuesta
                    WHERE pregunta = :pregunta
                ', [':pregunta'=>$pregunta['id']]);
                $pregunta['correctas'] = $this->db->getValue('
                    SELECT COUNT(*)
                    FROM respuesta
                    WHERE pregunta = :pregunta AND correcta = true
                ', [':pregunta'=>$pregunta['id']]);
                // agregar pregunta al arreglo
                $preguntas[] = $pregunta;
            }
        }
        return $preguntas;
    }

    private function preguntasTipo ($preguntas, $tipo)
    {
        $filtradas = [];
        foreach($preguntas as &$pregunta)
            if($pregunta['tipo']==$tipo)
                $filtradas[] = $pregunta;
        return $filtradas;
    }

}
