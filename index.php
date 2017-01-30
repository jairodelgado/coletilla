<?php
/*
 * Copyright (C) 2014 Jairo Rojas Delgado [jrdelgado@estudiantes.uci.cu].
 * 
 * This file is part of "La Coletilla v.1.0.".
 *
 * This software is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public Licence as published by
 * the Free Software Foundation, either version 3 of the Licence, or
 * any later version.
 *
 * This software is distributed in the hope tha it will be useful,
 * but WITHOUT ANY WARRANY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public Licence for more details.
 *
 * You should have received a copy of the GNU General Public Licence
 * along with the software. If not, see <http://www.gnu.org/licenses/>.
 */

include_once 'configuration.php';
include_once 'doctrine/doctrine_bootstrap.php';
include_once 'doctrine/entities/question.php';
include_once 'doctrine/entities/answer.php';
include_once 'doctrine/entities/user.php';

session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>La Coletilla</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link href="<?php echo app_host ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    </head>
    <body>   
        <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Panel de Navegación</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo app_host ?>"> La Coletilla </a>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo app_host ?>">Inicio</a></li>
                        <li><a href="<?php echo app_host ?>/ask">¡Enviar coletilla!</a></li>
                        <li><a href="<?php echo app_host ?>/suscription">Notificaciones</a></li>
                    </ul>
                    <ul class="nav navbar-nav pull-right">
                        <li><a href="<?php echo app_host ?>/about">Ayuda</a></li>
                        <li><a href="<?php echo app_host ?>/administration">Administración</a></li>
                    </ul>
                </div>
            </div>
        </nav>        
        <div class="container">
            <div class="col-xs-10 col-xs-offset-1">
                <?php
                if (isset($_GET["view"])) {

                    $view = $_GET["view"];
                } else {

                    $view = "question";
                }

                switch ($view) {
                    case "question" :
                        include_once 'views/question.php';
                        break;
                    case "ask" :
                        include_once 'views/ask.php';
                        break;
                    case "suscription" :
                        include_once 'views/suscription.php';
                        break;
                    case "administration" :
                        include_once 'views/administration.php';
                        break;
                    case "about" :
                        include_once 'views/about.php';
                        break;
                    case "logout" :
                        session_unset();
                        session_destroy();
                        include_once 'views/question.php';
                        break;
                    default :
                        echo "<div class='text-center'>Por el momento no puedo llevarte al lugar que quieres.</div>";
                }
                ?>
            </div>
        </div>

        <div class="row col-xs-10 col-xs-offset-1"> 
            <br>
            <p class="text-center"><small><a href="https://facultad6.uci.cu">Portal Gladiadores.</a><br> Todos los derechos reservados. 2014.</small></p>
        </div>

        <script src="<?php echo app_host ?>/bootstrap/js/jquery.js"></script>
        <script src="<?php echo app_host ?>/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
