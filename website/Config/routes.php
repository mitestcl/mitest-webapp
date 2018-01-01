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

/**
 * @file routes.php
 * Archivo de rutas "cortas" de la página web
 * @version 2014-11-09
 */

\sowerphp\core\Routing_Router::connect(
    '/u/*', ['controller' => 'usuarios', 'action' => 'mostrar']
);
\sowerphp\core\Routing_Router::connect(
    '/p/*', ['controller' => 'pruebas', 'action' => 'mostrar']
);
\sowerphp\core\Routing_Router::connect(
    '/r/*', ['controller' => 'pruebas', 'action' => 'resolver']
);
\sowerphp\core\Routing_Router::connect(
    '/d/*', ['controller' => 'pruebas', 'action' => 'descargar']
);
