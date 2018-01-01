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

// namespace del controlador
namespace website;

/**
 * Clase para el controlador asociado a la tabla categoria de la base de
 * datos
 * Comentario de la tabla: Tabla para categorías de las pruebas
 * Esta clase permite controlar las acciones entre el modelo y vista para la
 * tabla categoria
 * @author SowerPHP Code Generator
 * @version 2014-11-25 12:27:18
 */
class Controller_Categorias extends \Controller_App
{

    public function listar()
    {
        $this->set([
            'usuario' => $this->Auth->User->usuario,
            'categorias' => (new Model_Categorias())->getByUser($this->Auth->User->id),
        ]);
    }

    public function crear()
    {
        if (isset($_POST['submit'])) {
            $_POST['categoria'] = trim($_POST['categoria']);
            if (!isset($_POST['categoria'][0])) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Nombre de la categoría no puede estar en blanco', 'warning'
                );
                $this->redirect('/categorias/crear');
            }
            // guardar prueba
            $Categoria = new Model_Categoria();
            $Categoria->categoria = $_POST['categoria'];
            $Categoria->usuario = $this->Auth->User->id;
            $Categoria->publica = isset($_POST['publica']) ? 'true' : 'false';
            $Categoria->save();
            // mensaje y redireccionar
            \sowerphp\core\Model_Datasource_Session::message(
               'Categoría creada', 'ok'
            );
            $this->redirect('/categorias/listar');
        }
    }

    public function editar($id)
    {
        $Categoria = new Model_Categoria($id);
        // si el registro que se quiere editar y no existe error
        if (!$Categoria->exists()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La categoría solicitada no existe, no se puede editar',
                'error'
            );
            $this->redirect('/categorias/listar');
        }
        // si no es el dueño de la categoría error
        if ($Categoria->usuario!=$this->Auth->User->id) {
            \sowerphp\core\Model_Datasource_Session::message(
                'No es el autor de la categoría, no se puede editar',
                'error'
            );
            $this->redirect('/categorias/listar');
        }
        // editar si se envió formulario
        if (isset($_POST['submit'])) {
            $_POST['categoria'] = trim($_POST['categoria']);
            if (!isset($_POST['categoria'][0])) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Nombre de la categoría no puede estar en blanco', 'warning'
                );
                $this->redirect('/categorias/crear');
            }
            // guardar prueba
            $Categoria->categoria = $_POST['categoria'];
            $Categoria->publica = isset($_POST['publica']) ? 'true' : 'false';
            $Categoria->save();
            // mensaje y redireccionar
            \sowerphp\core\Model_Datasource_Session::message(
               'Categoría editada', 'ok'
            );
            $this->redirect('/categorias/listar');
        } else {
            $this->set('Categoria', $Categoria);
        }
    }

    public function eliminar($id)
    {
        $Categoria = new Model_Categoria($id);
        // si el registro que se quiere editar y no existe error
        if (!$Categoria->exists()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La categoría solicitada no existe, no se puede eliminar',
                'error'
            );
            $this->redirect('/categorias/listar');
        }
        // si no es el dueño de la categoría error
        if ($Categoria->usuario!=$this->Auth->User->id) {
            \sowerphp\core\Model_Datasource_Session::message(
                'No es el autor de la categoría, no se puede eliminar',
                'error'
            );
            $this->redirect('/categorias/listar');
        }
        // si tiene pruebas creadas no se puede eliminar
        if ($Categoria->pruebasCount()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'Categoría tiene pruebas asociadas, no se puede eliminar',
                'error'
            );
            $this->redirect('/categorias/listar');
        }
        // eliminar prueba
        $Categoria->delete();
        \sowerphp\core\Model_Datasource_Session::message(
            'Categoría eliminada', 'ok'
        );
        $this->redirect('/categorias/listar');
    }

    public function subir($id)
    {
        $Categoria = new Model_Categoria($id);
        // si el registro que se quiere editar y no existe error
        if (!$Categoria->exists()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La categoría solicitada no existe, no se puede editar',
                'error'
            );
            $this->redirect('/categorias/listar');
        }
        // si no es el dueño de la categoría error
        if ($Categoria->usuario!=$this->Auth->User->id) {
            \sowerphp\core\Model_Datasource_Session::message(
                'No es el autor de la categoría, no se puede editar',
                'error'
            );
            $this->redirect('/categorias/listar');
        }
        // solo se procesa si el orden es mayor a 1
        if($Categoria->orden>1) {
            $Categoria->intercambiarOrden($Categoria->orden-1);
        }
        // redireccionar
        $this->redirect('/categorias/listar');
    }

    public function bajar($id)
    {
        $Categoria = new Model_Categoria($id);
        // si el registro que se quiere editar y no existe error
        if (!$Categoria->exists()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'La categoría solicitada no existe, no se puede editar',
                'error'
            );
            $this->redirect('/categorias/listar');
        }
        // si no es el dueño de la categoría error
        if ($Categoria->usuario!=$this->Auth->User->id) {
            \sowerphp\core\Model_Datasource_Session::message(
                'No es el autor de la categoría, no se puede editar',
                'error'
            );
            $this->redirect('/categorias/listar');
        }
        // solo se procesa si el orden es menor que el maximo existente
        $Categorias = new Model_Categorias();
        $Categorias->setWhereStatement(['usuario = :usuario'], [':usuario'=>$this->Auth->User->id]);
        if($Categoria->orden<$Categorias->getMax('orden')) {
            $Categoria->intercambiarOrden($Categoria->orden+1);
        }
        // redireccionar
        $this->redirect('/categorias/listar');
    }

}
