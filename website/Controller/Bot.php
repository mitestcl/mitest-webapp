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
 * Controlador para MiTeStBot
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2015-07-04
 */
class Controller_Bot extends \sowerphp\app\Controller_Bot
{

    protected $help = [
        'usuario'       => 'dime un usuario a explorar',
        'categoria'     => 'dime una categoría a revisar',
        'resolver'      => 'dime la prueba que quieres resolver',
        'cancel'        => 'cancelaré la acción en curso',
        'support'       => 'envía un mensaje a mis creadores',
    ];
    private $actual; ///< Objeto con Usuario, Categoria, Prueba y Pregunta actualmente en uso

    /**
     * Método que se llama antes de ejecutar el comando
     * @param command
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-07-04
     */
    protected function beforeRun($command)
    {
        parent::beforeRun($command);
        $this->actual = $this->Cache->get('bot_mitest_actual_'.$this->Bot->getFrom()->id);
        if (!$this->actual) {
            $this->actual = (object) [
                'Usuario' => null,
                'Categoria' => null,
                'Prueba' => null,
                'Pregunta' => null,
            ];
        }
    }

    /**
     * Método que se llama después de ejecutar el comano
     * @param commad
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-07-04
     */
    protected function afterRun($command)
    {
        $this->Cache->set('bot_mitest_actual_'.$this->Bot->getFrom()->id, $this->actual);
        parent::afterRun($command);
    }

    /**
     * Comando que se ejecuta al iniciar el bot, podrá lanzar directamente un
     * usuario, una categoría o la resolución de una prueba
     * @param token String con formato u:usuario, c:categoria o r:prueba (opcional)
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-07-04
     */
    protected function _bot_start($token = null)
    {
        if ($token and isset($token[2]) and $token[1]==':') {
            if ($token[0]=='u') $this->_bot_usuario(substr($token, 2));
            else if ($token[0]=='c') $this->_bot_categoria(substr($token, 2));
            else if ($token[0]=='r') $this->_bot_resolver(substr($token, 2));
        } else {
            $this->setNextCommand('usuario');
            $this->Bot->send('Dime el usuario que quieres ver sus categorías');
        }
    }

    /**
     * Comando que permite elegir un usuario y muestra sus categorías
     * @param usuario Nombre del usuario que se desea explorar
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-07-04
     */
    protected function _bot_usuario($usuario = null)
    {
        if (!$usuario or is_numeric($usuario)) {
            $this->setNextCommand('usuario');
            $this->Bot->send('Dime el usuario que quieres ver sus categorías');
        } else {
            $this->actual->Usuario = new \sowerphp\app\Sistema\Usuarios\Model_Usuario($usuario);
            if (!$this->actual->Usuario->exists())
                $this->actual->Usuario = new \sowerphp\app\Sistema\Usuarios\Model_Usuario(strtolower($usuario));
            if (!$this->actual->Usuario->exists()) {
                $this->setNextCommand('usuario');
                $this->Bot->send('Lo siento, no encuentro el usuario '.$usuario);
            } else {
                $this->setNextCommand('categoria');
                $this->Bot->sendKeyboard(
                    'Selecciona la categoría del usuario que quieres explorar',
                    $this->getKeyboard((new Model_Categorias())->getListByUser($this->actual->Usuario->id, true))
                );
            }
        }
    }

    /**
     * Comando que permite elegir una categoría y muestra sus pruebas
     * @param categoria ID de la categoría que se quieren revisar sus pruebas
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-07-04
     */
    protected function _bot_categoria($categoria = null)
    {
        if (!$categoria and !$this->actual->Usuario) {
            $this->setNextCommand('usuario');
            $this->Bot->send('Antes debes indicarme un /usuario');
        }
        else if (!$categoria or (is_numeric($categoria) and $categoria > 2000000000)) {
            $this->setNextCommand('categoria');
            $this->Bot->sendKeyboard(
                'Selecciona la categoría del usuario que quieres explorar',
                $this->getKeyboard((new Model_Categorias())->getListByUser($this->actual->Usuario->id, true))
            );
        }
        else {
            $this->actual->Categoria = new Model_Categoria($categoria);
            if (!$this->actual->Categoria->exists()) {
                $this->setNextCommand('categoria');
                $this->Bot->sendKeyboard(
                    'Lo siento, no encuentro la categoría solicitada. Selecciona una de las que conozco',
                    $this->getKeyboard((new Model_Categorias())->getListByUser($this->actual->Usuario->id, true))
                );
            } else {
                if (!$this->actual->Usuario or $this->actual->Usuario->id!=$this->actual->Categoria->usuario) {
                    $this->actual->Usuario = $this->actual->Categoria->getUsuario();
                }
                $this->setNextCommand('resolver');
                $this->Bot->sendKeyboard(
                    'Selecciona una prueba que quieras resolver',
                    $this->getKeyboard((new Model_Pruebas())->getListByCategoria($categoria, true))
                );
            }
        }
    }

    /**
     * Comando qe permite seleccionar una prueba y empezar a resolverla
     * @param prueba ID de la prueba que se desea resolver
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-07-20
     */
    protected function _bot_resolver($prueba = null)
    {
        if (!$prueba and !$this->actual->Categoria) {
            $this->setNextCommand('categoria');
            $this->Bot->send('Antes debes indicarme una /categoria');
        }
        else if (!$prueba or (is_numeric($prueba) and $prueba > 2000000000)) {
            $this->setNextCommand('resolver');
            $this->Bot->sendKeyboard(
                'Selecciona una prueba que quieras resolver',
                $this->getKeyboard((new Model_Pruebas())->getListByCategoria($this->actual->Categoria->id, true))
            );
        }
        else {
            $this->actual->Prueba = new Model_Prueba($prueba);
            if (!$this->actual->Prueba->exists()) {
                $this->setNextCommand('resolver');
                $this->Bot->sendKeyboard(
                    'Lo siento, no encuentro la prueba solicitada. Selecciona una de las que conozco',
                    $this->getKeyboard((new Model_Pruebas())->getListByCategoria($this->actual->Categoria->id, true))
                );
            } else {
                if (!$this->actual->Categoria or $this->actual->Categoria->id!=$this->actual->Prueba->categoria) {
                    $this->actual->Categoria = $this->actual->Prueba->getCategoria();
                    if (!$this->actual->Usuario or $this->actual->Usuario->id!=$this->actual->Categoria->usuario) {
                        $this->actual->Usuario = $this->actual->Categoria->getUsuario();
                    }
                }
                $this->actual->Prueba->total_preguntas = 0;
                $this->actual->Prueba->respuestas_correctas = 0;
                $this->Bot->send('Resolverás la prueba "'.$this->actual->Prueba->prueba.'"');
                $this->_bot_pregunta();
            }
        }
    }

    /**
     * Comando que permite ir resolviendo las preguntas y enviando la respuesta
     * @param respuestas La respuesta de la pregunta que se hizo
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-07-04
     */
    protected function _bot_pregunta($respuestas = null)
    {
        if (!isset($this->actual->Prueba)) {
            $this->setNextCommand('resolver');
            $this->Bot->send('Antes debes indicarme una prueba a /resolver');
        }
        else {
            if ($respuestas) {
                $respuestas = array_map('strtoupper', func_get_args());
                sort($respuestas);
                sort($this->actual->Pregunta->correctas);
                if ($respuestas == $this->actual->Pregunta->correctas) {
                    $this->Bot->send('Respuesta correcta '."\xF0\x9F\x91\x8D");
                    $this->actual->Prueba->respuestas_correctas++;
                } else {
                    $this->Bot->send('Respuesta incorrecta '."\xF0\x9F\x91\x8E");
                }
                if ($this->actual->Pregunta->explicacion)
                    $this->Bot->send("\n\n".'Explicación: '.$this->actual->Pregunta->explicacion);
            }
            if ($this->actual->Prueba->preguntas) {
                $this->Bot->sendChatAction();
                $this->actual->Pregunta = array_shift($this->actual->Prueba->preguntas);
                $this->actual->Prueba->total_preguntas++;
                $this->setNextCommand('pregunta');
                $msg = $this->actual->Pregunta->pregunta."\n\n";
                $letra = 65;
                $alternativas = [];
                $this->actual->Pregunta->correctas = [];
                foreach ($this->actual->Pregunta->respuestas as $Respuesta) {
                    $msg .= chr($letra).') '.$Respuesta->respuesta."\n";
                    $alternativas[] = chr($letra);
                    if ($Respuesta->correcta)
                        $this->actual->Pregunta->correctas[] = chr($letra);
                    $letra++;
                }
                $n_correctas = count($this->actual->Pregunta->answersCorrect());
                $keyboard = $this->getKeyboard($this->crearAlternativas($alternativas, $n_correctas));
                if ($this->actual->Pregunta->imagen_name) {
                    $file = TMP.'/'.$this->actual->Pregunta->imagen_name;
                    file_put_contents($file, $this->actual->Pregunta->getImagenData());
                    $this->Bot->sendPhotoKeyboard($file, $keyboard, $msg);
                    unlink($file);
                } else {
                    $this->Bot->sendKeyboard($msg, $keyboard);
                }
            } else {
                $this->setNextCommand();
                $this->Bot->send('Prueba terminada, respuestas correctas '.$this->actual->Prueba->respuestas_correctas.' de '.$this->actual->Prueba->total_preguntas."\n\n".'Puedes /resolver otra prueba de la categoría '.$this->actual->Categoria->categoria);
            }
        }
    }

    /**
     * Método que arma el arreglo con las alternativas, cada alternativa tendrá
     * tantas letras como alternativas correctas existan
     * @param letras Arreglo con las letras
     * @param correctas Cantidad de alternativas correctas
     * @return Arreglo con las alternativas correctas
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-07-04
     */
    private function crearAlternativas($letras, $correctas)
    {
        require_once ('Math/Combinatorics.php');
        $combinations = (new \Math_Combinatorics())->combinations($letras, $correctas);
        foreach ($combinations as &$c)
            $c = implode(' ', $c);
        return $combinations;
    }

}
