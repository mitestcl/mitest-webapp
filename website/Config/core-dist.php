<?php

/**
 * MiTeSt
 * Copyright (C) 2015 Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
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

/** ESTE ARCHIVO SE DEBE CONFIGURAR Y RENOMBRAR A core.php */

/**
 * @file core.php
 * Configuración propia de cada página o aplicación
 * @version 2015-06-22
 */

// Tema de la página (diseño)
\sowerphp\core\Configure::write('page.layout', 'mitest');

// logo ASCII
$logo = <<< EOF
  __  __ _ _____    ____  _
 |  \/  (_)_   _|__/ ___|| |_
 | |\/| | | | |/ _ \___ \| __|
 | |  | | | | |  __/___) | |_
 |_|  |_|_| |_|\___|____/ \__|   versión Beta

EOF;

// Textos de la página
\sowerphp\core\Configure::write('page.header.title', 'MiTeSt');
\sowerphp\core\Configure::write('page.body.title', 'MiTeSt');
\sowerphp\core\Configure::write('page.footer', '&copy; 2012-2015 MiTeSt<br/><span>Un proyecto de <a href="https://sasco.cl">SASCO SpA</a></span>');

// Menú principal del sitio web
\sowerphp\core\Configure::write('nav.website', array(
    '/usuarios' => array('name'=>'Usuarios', 'desc'=>'Listado de usuarios'),
    '/descargas' => array('name'=>'Descargas', 'desc'=>'Descargas de software para ejecutar las pruebas'),
    '/contacto' => array('name'=>'Contacto', 'desc'=>'Página de contacto con desarrollador del proyecto'),
));

// Menú principal de la aplicación
\sowerphp\core\Configure::write('nav.app', array(
    '/pruebas' => array('name'=>'Pruebas', 'desc'=>'Acceso a gestión de pruebas'),
    '/sistema' => array('name'=>'Sistema', 'desc'=>'Acceso área de administración'),
));

// Base de datos
\sowerphp\core\Configure::write('database.default', array(
    'type' => 'PostgreSQL',
    'name' => 'mitest',
    'user' => '',
    'pass' => ''
));

// Formato por defecto de las pruebas que se descargan
\sowerphp\core\Configure::write('test.format', 'json'); // mt, xml o json

// Agregar mimetype para archivos mt
\sowerphp\core\Network_Response::setMimetype('mt', 'text/plain');

// Configuración para el correo electrónico
\sowerphp\core\Configure::write('email.default', [
    'type' => 'smtp',
    'host' => 'ssl://smtp.gmail.com',
    'port' => 465,
    'user' => '',
    'pass' => '',
    'to' => '',
]);

// Configuración para reCAPTCHA
/*\sowerphp\core\Configure::write('recaptcha', [
    'public_key' => '',
    'private_key' => '',
]);*/

// Configuración para auto registro de usuarios
\sowerphp\core\Configure::write('app.self_register', [
    'groups' => ['usuarios']
]);
