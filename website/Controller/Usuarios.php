<?php

/**
 * MiTeSt
 * Copyright (C) 2015 Esteban De La Fuente Rubio (esteban[at]delaf.cl)
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

namespace website;

/**
 * Clase final para el controlador asociado a la tabla usuario de la base de datos
 * Comentario de la tabla: Tabla para usuarios del sistema
 * Esta clase permite controlar las acciones entre el modelo y vista para la tabla usuario
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 * @version 2014-12-02
 */
final class Controller_Usuarios extends \Controller_App
{

    public function beforeFilter()
    {
        $this->Auth->allow('index', 'mostrar');
        parent::beforeFilter();
    }

    /**
     * Acción para mostrar el listado de usuarios
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-11-08
     */
    public function index()
    {
        $this->set('usuarios', (new Model_Usuarios())->conCategoriasPruebasPublicas());
    }

    /**
     * Controlador para mostrar el perfil público de un usuario
     * @param usuario Usuario que se quiere visualizar
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-11-08
     */
    public function mostrar($usuario)
    {
        $Usuario = new \sowerphp\app\Sistema\Usuarios\Model_Usuario($usuario);
        // si el usuario no existe error
        if (!$Usuario->exists()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'Usuario no válido', 'error'
            );
            $this->redirect('/usuarios');
        }
        // buscar pruebas de cada categoria
        $categorias = (new Model_Categorias())->getListByUser(
            $Usuario->id, true
        );
        // el usuario no tiene categorías públicas
        if (!$categorias) {
            \sowerphp\core\Model_Datasource_Session::message(
                'Usuario <em>'.$usuario.'</em> no tiene categorías públicas', 'warning'
            );
            $this->redirect('/usuarios');
        }
        $Pruebas = new Model_Pruebas();
        foreach($categorias as &$categoria) {
            $categoria['pruebas'] = $Pruebas->getByCategoria(
                $categoria['id'], true
            );
        }
        // setear variables para la vista
        $this->set([
            'usuario' => $Usuario->usuario,
            'nombre' => $Usuario->nombre,
            'categorias' => $categorias,
            'header_title' => $Usuario->usuario,
        ]);
    }

    public function _api_crud_GET($usuario = null)
    {
        // entregar colección de usuarios
        if ($usuario===null) {
            return (new Model_Usuarios())->conCategoriasPruebasPublicas();
        }
        // entregar un usuario
        else {
            $Usuario = new \sowerphp\app\Sistema\Usuarios\Model_Usuario($usuario);
            // si el usuario no existe error
            if (!$Usuario->exists()) {
                $this->Api->send('Usuario no válido', 404);
            }
            // crear datos del usuario
            $categorias = (new Model_Categorias())->getListByUser(
                $Usuario->id, true
            );
            $Pruebas = new Model_Pruebas();
            foreach($categorias as &$categoria) {
                $categoria['pruebas'] = $Pruebas->getByCategoria(
                    $categoria['id'], true
                );
            }
            return [
                'usuario' => $Usuario->usuario,
                'nombre' => $Usuario->nombre,
                'categorias' => $categorias,
            ];
        }
    }

}
