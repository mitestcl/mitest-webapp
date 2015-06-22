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
 * Clase para mapear la tabla prueba de la base de datos
 * Comentario de la tabla: Tabla para pruebas de los usuarios
 * Esta clase permite trabajar sobre un registro de la tabla prueba
 * @author SowerPHP Code Generator
 * @version 2014-07-24 18:11:39
 */
class Model_Prueba extends \Model_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'prueba'; ///< Tabla del modelo

    // Atributos de la clase (columnas en la base de datos)
    public $id; ///< Identificador de la prueba: integer(32) NOT NULL DEFAULT 'nextval('prueba_id_seq'::regclass)' AUTO PK
    public $prueba; ///< Título o nombre de la prueba: character varying(100) NOT NULL DEFAULT ''
    public $descripcion; ///< Descripción de la prueba: text() NULL DEFAULT ''
    public $categoria; ///< Categoría a la que pertenece la prueba: integer(32) NOT NULL DEFAULT '' FK:categoria.id
    public $creada; ///< Cuando fue creada: timestamp without time zone() NOT NULL DEFAULT 'now()'
    public $modificada; ///< Cuando fue modificada por última vez: timestamp without time zone() NOT NULL DEFAULT 'now()'
    public $publica; ///< Indica si es visible para todos o solo para su dueño: boolean() NOT NULL DEFAULT 'true'
    public $orden; ///< Orden en que debe ser listada: smallint(16) NOT NULL DEFAULT '0'

    // Información de las columnas de la tabla en la base de datos
    public static $columnsInfo = array(
        'id' => array(
            'name'      => 'Id',
            'comment'   => 'Identificador de la prueba',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => false,
            'default'   => "nextval('prueba_id_seq'::regclass)",
            'auto'      => true,
            'pk'        => true,
            'fk'        => null
        ),
        'prueba' => array(
            'name'      => 'Prueba',
            'comment'   => 'Título o nombre de la prueba',
            'type'      => 'character varying',
            'length'    => 100,
            'null'      => false,
            'default'   => "",
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'descripcion' => array(
            'name'      => 'Descripcion',
            'comment'   => 'Descripción de la prueba',
            'type'      => 'text',
            'length'    => null,
            'null'      => true,
            'default'   => "",
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'categoria' => array(
            'name'      => 'Categoria',
            'comment'   => 'Categoría a la que pertenece la prueba',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => false,
            'default'   => "",
            'auto'      => false,
            'pk'        => false,
            'fk'        => array('table' => 'categoria', 'column' => 'id')
        ),
        'creada' => array(
            'name'      => 'Creada',
            'comment'   => 'Cuando fue creada',
            'type'      => 'timestamp without time zone',
            'length'    => null,
            'null'      => false,
            'default'   => "now()",
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'modificada' => array(
            'name'      => 'Modificada',
            'comment'   => 'Cuando fue modificada por última vez',
            'type'      => 'timestamp without time zone',
            'length'    => null,
            'null'      => false,
            'default'   => "now()",
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
            'default'   => "true",
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
            'default'   => "0",
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),

    );

    // Comentario de la tabla en la base de datos
    public static $tableComment = 'Tabla para pruebas de los usuarios';

    public static $fkNamespace = array(
        'Model_Categoria' => 'website'
    ); ///< Namespaces que utiliza esta clase

    public $autor; ///< Autor de la prueba (nombre completo + usuario)
    public $preguntas; ///< Listado de preguntas de la prueba
    public $generada; ///< Timestamp de cuando se generó la prueba

    public function __construct($id = null, $onlypublics = true, $random = true, $onlyActive = true)
    {
        // llamar al constructor padre con el id para recuperar el objeto
        parent::__construct($id);
        // si se indicó una prueba en el constructor se crea la misma
        if ($id and $this->exists()) {
            // ajustar fechas y horas
            $dot = strpos($this->creada, '.');
            if ($dot)
                $this->creada = substr($this->creada, 0, $dot);
            $dot = strpos($this->modificada, '.');
            if ($dot)
                $this->modificada = substr($this->modificada, 0, $dot);
            $this->generada = date(\sowerphp\core\Configure::read('time.format'));
            // obtener autor
            $this->autor = $this->getCategoria()->getUsuario()->nombre.' ('.$this->getCategoria()->getUsuario()->usuario.')';
            // obtener preguntas
            $this->loadQuestions($onlypublics, $random, $onlyActive);
        }
    }

    /**
     * Indica si una prueba existe, podrá indicar que no existe si la prueba
     * es privada y se requiere que sea pública
     */
    public function exists($onlypublics = false)
    {
        // si no existe entregar falso
        if (!$this->id) return false;
        $existe = (boolean)$this->db->getValue(
            'SELECT COUNT(*) FROM prueba WHERE id = :prueba',
            [':prueba'=>$this->id]
        );
        if (!$existe) return false;
        // si existe, pero se requieren solo públicas y no es pública
        // ni tampoco su categoría padre -> error
        if (
            $onlypublics && (
                $this->publica=='f' ||
                $this->getCategoria()->publica=='f'
            )
        ) {
            return false;
        }
        return true;
    }

    public function save()
    {
        if (!$this->exists()) {
            $this->orden = $this->db->getValue('
                SELECT CASE WHEN MAX(orden)>0 THEN MAX(orden)+1 ELSE 1 END
                FROM prueba
                WHERE categoria = :categoria
            ', [':categoria'=>$this->categoria]);
        }
        parent::save();
    }

    private function loadQuestions($onlypublics = true, $random = true, $onlyActive = true)
    {
        $Preguntas = new Model_Preguntas();
        $where = ['prueba = :prueba'];
        if ($onlyActive)
            $where[] = 'activa = true';
        if ($onlypublics)
            $where[] = 'publica = true';
        $Preguntas->setWhereStatement($where, [':prueba'=>$this->id]);
        if ($random) $Preguntas->setOrderByStatement('RANDOM()');
        else $Preguntas->setOrderByStatement('id');
        $this->preguntas = $Preguntas->getObjects();
        foreach($this->preguntas as &$pregunta) {
            $pregunta->loadAnswers($random);
        }
    }

    public function questions()
    {
        return count($this->preguntas);
    }

    public function getAnswers()
    {
        $answers = array();
        foreach($this->preguntas as &$question) {
            foreach($question->respuestas as &$answer) {
                if($answer->correcta) $answers[$question->id][] = $answer->id;
            }
            sort($answers[$question->id]);
        }
        return $answers;
    }

    public function getJSON()
    {
        // generar cabecera de la prueba
        $data = array(
            'categoria' => $this->getCategoria()->categoria,
            'prueba' => $this->prueba,
            'autor' => $this->autor,
            'generada' => $this->generada,
            'creada' => $this->creada,
            'modificada' => $this->modificada,
        );
        // generar preguntas
        $data['preguntas'] = array();
        foreach($this->preguntas as &$question) {
            $alternativas = array();
            foreach($question->respuestas as &$answer) {
                $alternativas[] = array(
                    'correcta' => $answer->correcta=='t' ? true : false,
                    'alternativa' => $answer->respuesta,
                );
            }
            $data['preguntas'][] = array(
                'tipo' => $question->getTipo()->tipo,
                'pregunta' => $question->pregunta,
                'imagen' => $question->imagen_name,
                'explicacion' => $question->explicacion,
                'alternativas' => $alternativas,
            );
        }
        // entregar prueba
        return json_encode($data);
    }

    public function getXML($ordenado = false)
    {
        // buffer para guardar prueba
        $buffer = '';
        // generar cabecera de la prueba
        $buffer .= '<?xml version="1.0" encoding="UTF-8" ?>'.($ordenado?"\n":'');
        $buffer .= '<test>'.($ordenado?"\n":'');
        $buffer .= ($ordenado?"\t":'').'<categoria>'.$this->getCategoria()->categoria.'</categoria>'.($ordenado?"\n":'');
        $buffer .= ($ordenado?"\t":'').'<prueba>'.$this->prueba.'</prueba>'.($ordenado?"\n":'');
        $buffer .= ($ordenado?"\t":'').'<autor>'.$this->autor.'</autor>'.($ordenado?"\n":'');
        $buffer .= ($ordenado?"\t":'').'<generada>'.date('d M Y, H:i').'</generada>'.($ordenado?"\n":'');
        $buffer .= ($ordenado?"\t":'').'<creada>'.$this->creada.'</creada>'.($ordenado?"\n":'');
        $buffer .= ($ordenado?"\t":'').'<modificada>'.$this->modificada.'</modificada>'.($ordenado?"\n":'');
        // generar preguntas
        $buffer .= ($ordenado?"\t":'').'<preguntas>'.($ordenado?"\n":'');
        foreach($this->preguntas as &$question) {
            $buffer .= ($ordenado?"\t\t":'').'<question>'.($ordenado?"\n":'');
            $buffer .= ($ordenado?"\t\t\t":'').'<tipo>'.$question->getTipo()->tipo.'</tipo>'.($ordenado?"\n":'');
            $buffer .= ($ordenado?"\t\t\t":'').'<pregunta>'.$question->pregunta.'</pregunta>'.($ordenado?"\n":'');
            $buffer .= ($ordenado?"\t\t\t":'').'<alternativas>'.($ordenado?"\n":'');
            foreach($question->respuestas as &$answer) {
                $buffer .= ($ordenado?"\t\t\t\t":'').'<answer>'.($ordenado?"\n":'');
                $buffer .= ($ordenado?"\t\t\t\t\t":'').'<correcta>'.($answer->correcta=='t'?'si':'no').'</correcta>'.($ordenado?"\n":'');
                $buffer .= ($ordenado?"\t\t\t\t\t":'').'<alternativa>'.$answer->respuesta.'</alternativa>'.($ordenado?"\n":'');
                $buffer .= ($ordenado?"\t\t\t\t":'').'</answer>'.($ordenado?"\n":'');
            }
            $buffer .= ($ordenado?"\t\t\t":'').'</alternativas>'.($ordenado?"\n":'');
            $buffer .= ($ordenado?"\t\t\t":'').'<imagen>'.$question->imagen_name.'</imagen>'.($ordenado?"\n":'');
            $buffer .= ($ordenado?"\t\t\t":'').'<explicacion>'.$question->explicacion.'</explicacion>'.($ordenado?"\n":'');
            $buffer .=  ($ordenado?"\t\t":'').'</question>'.($ordenado?"\n":'');
        }
        // fin de la prueba
        $buffer .= ($ordenado?"\t":'').'</preguntas>'.($ordenado?"\n":'');
        $buffer .= '</test>'.($ordenado?"\n":'');
        // entregar prueba
        return $buffer;
    }

    public function getMT()
    {
        // buffer para guardar prueba
        $buffer = '';
        // generar cabecera de la prueba
        $buffer .= '##'."\n";
        $buffer .= '# Categoría  : '.$this->getCategoria()->categoria."\n";
        $buffer .= '# Título     : '.$this->prueba."\n";
        $buffer .= '# Autor      : '.$this->autor."\n";
        $buffer .= '# Generada   : '.$this->generada."\n";
        $buffer .= '# Creada     : '.$this->creada."\n";
        $buffer .= '# Modificada : '.$this->modificada."\n";
        $buffer .= '##'."\n\n";
        $buffer .= '!:'.$this->prueba.':'.$this->getCategoria()->categoria.':'.$this->autor."\n\n";
        // generar preguntas
        $n = 0;
        foreach($this->preguntas as &$question) {
            $buffer .= '# tipo: '.$question->getTipo()->tipo."\n";
            $buffer .= (++$n).' '.$question->pregunta."\n";
            foreach($question->respuestas as &$answer) {
                $buffer .= ($answer->correcta=='t'?'*':'').$answer->respuesta."\n";
            }
            $buffer .= '(I) '.$question->imagen_name."\n";
            $buffer .= '(E) '.$question->explicacion."\n";
            $buffer .= "\n";
        }
        // entregar prueba
        return $buffer;
    }

    public function getImagenes()
    {
        return $this->db->getTable('
            SELECT imagen_data as data, imagen_size as size, imagen_type as type, imagen_name as name
            FROM pregunta
            WHERE prueba = :id
        ', [':id'=>$this->id]);
    }

    public function intercambiarOrden($orden)
    {
        $this->db->query('
            UPDATE prueba
            SET orden = :orden1
            WHERE categoria = :categoria AND orden = :orden2
        ', [':categoria'=>$this->categoria, ':orden1'=>$this->orden, ':orden2'=>$orden]);
        $this->db->query('
            UPDATE prueba
            SET orden = :orden
            WHERE id = :id
        ', [':id'=>$this->id, ':orden'=>$orden]);
    }

    /**
     * Método que elimina las preguntas que no se hayan indicado
     * @param dejar Arreglo con IDs de preguntas que se deben dejar
     */
    public function dejarPreguntas($dejar)
    {
        $dejar = array_map('intval', $dejar);
        $this->db->query('
            DELETE FROM pregunta
            WHERE prueba = :id AND id IN (
                SELECT id
                FROM pregunta
                WHERE
                    prueba = :id
                    AND id NOT IN ('.implode(', ', $dejar).')
            )
        ', [':id'=>$this->id]);
    }

    public function totalPreguntas($soloActivas = true)
    {
        $where = $soloActivas ? 'AND activa = true' : '';
        return $this->db->getValue('
            SELECT COUNT(*)
            FROM pregunta
            WHERE prueba = :id '.$where.'
        ', [':id'=>$this->id]);
    }

    public function preguntasPorTipo($soloActivas = true)
    {
        $where = $soloActivas ? 'AND p.activa = true' : '';
        return $this->db->getAssociativeArray('
            SELECT t.tipo, count(*)
            FROM pregunta AS p, tipo AS t
            WHERE
                p.tipo = t.id
                AND prueba = :id '.$where.'
            GROUP BY t.tipo, t.peso
            ORDER BY t.peso
        ', [':id'=>$this->id]);
    }

}
