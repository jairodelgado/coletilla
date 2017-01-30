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
 * along with the Octree Library. If not, see <http://www.gnu.org/licenses/>.
 */

if (isset($_POST["checker"])) {
    if (isset($_POST["txt_nick"]) && !empty($_POST["txt_nick"]) && isset($_POST["txt_password"]) && !empty($_POST["txt_password"])) {

        $name = $_POST["txt_nick"];
        $password = $_POST["txt_password"];

        $user = $db_manager->getRepository("user")->findOneBy(array("nick_name" => $name, "secret" => $password));
        if (isset($user)) {

            $_SESSION["logged"] = true;
            $_SESSION["user"] = $user;
        } else {

            echo '<div class="text-center">Parece que te has equivocado de usuario o contraseña.</div>';
        }
    } else {

        echo '<div class="text-center">Upsss... completa los campos en blanco para continuar.</div>';
    }
}


if (isset($_SESSION["logged"])) {

    if ($_SESSION["user"]->getAdministrator() == true) {

        if (isset($_GET["aproveQuestion"])) {

            $question = $db_manager->getRepository("question")->find(intval($_GET["aproveQuestion"]));
            if (isset($question)) {

                $question->setPublished(true);
                $db_manager->persist($question);
                $db_manager->flush();

                $users = $db_manager->getRepository("user")->findAll();
                
                include 'XMPPHP/XMPP.php';
                
                $conn = new XMPPHP_XMPP(xmpp_host, xmpp_port, xmpp_user, xmpp_password, 'gladiadores-notifier', xmpp_host);

                try {
                    $conn->connect();
                    $conn->processUntil('session_start');
                   
                    foreach ($users as $user) {

                        if ($user->getEmail() != "") {
                            
                            $conn->message($user->getEmail() , '<hr/><center style="font-family:Helvetica,Arial,sans-serif;"><h1><strong style="color: #d9230f;">¡La Coletilla en linea!</strong></h1><a href="https://facultad6.uci.cu" style="color: gray;">Portal Gladiadores</a><h2><a href="' . app_host . '/question/ ' . $question->getId() .  '" style="color: black;">' . $question->getContent() . '</a></h2><a href="' . app_host . '/question/' . $question->getId() .  '" style="color: #d9230f;">Ver</a> - <a href="' . app_host . '/question/' . $question->getId() . '#comments" style="color: #d9230f;">Comentar</a></center>');
                        }
                    }
                    
                    $conn->disconnect();
                } catch (XMPPHP_Exception $e) {
                    
                }
            }
        }

        if (isset($_GET["aproveAnswer"])) {

            $answer = $db_manager->getRepository("answer")->find(intval($_GET["aproveAnswer"]));
            if (isset($answer)) {

                $answer->setPublished(true);
                $db_manager->persist($answer);
                $db_manager->flush();
            }
        }

        if (isset($_GET["removeQuestion"])) {

            $question = $db_manager->getRepository("question")->find(intval($_GET["removeQuestion"]));
            if (isset($question)) {

                $question->setPublished(true);
                $db_manager->remove($question);
                $db_manager->flush();
            }
        }

        if (isset($_GET["removeAnswer"])) {

            $answer = $db_manager->getRepository("answer")->find(intval($_GET["removeAnswer"]));
            if (isset($answer)) {

                $db_manager->remove($answer);
                $db_manager->flush();
            }
        }

        $unmoderated_questions = $db_manager->getRepository("question")->findBy(array("published" => "false"), array("post_date" => "ASC"));
        $unmoderated_answers = $db_manager->getRepository("answer")->findBy(array("published" => "false"), array("post_date" => "ASC"));
        $moderated_questions = $db_manager->getRepository("question")->findBy(array("published" => "true"), array("post_date" => "ASC"));
        ?>
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1">
                <div class="page-header">
                    <h1><small>Esperando moderación.</small></h1>
                </div>
            </div>
        </div>
        <div class="row col-xs-8 col-xs-offset-2">
            <?php
            $iterator = 1;

            foreach ($unmoderated_questions as $question) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Pregunta <?php echo $iterator++; ?>: <small class="pull-right"><span class="glyphicon glyphicon-calendar"></span> <?php echo $question->getPost_date()->format("d-m-Y"); ?> <span class="glyphicon glyphicon-time"></span> <?php echo $question->getPost_date()->format("H:i"); ?></small></h3>
                    </div>
                    <div class="panel-body text-justify">
                        <?php echo $question->getContent(); ?>
                        <hr>
                        <div class="pull-right">
                            <a href="<?php echo app_host ?>/administration/publish/question/<?php echo $question->getId(); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Publicar</a>
                            <a href="<?php echo app_host ?>/administration/remove/question/<?php echo $question->getId(); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-remove-sign"></span> Eliminar</a>
                        </div>
                    </div>
                </div>
                <?php
            }

            foreach ($unmoderated_answers as $answer) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $answer->getUser()->getNick_name(); ?> dijo: <small class="pull-right"><span class="glyphicon glyphicon-calendar"></span> <?php echo $answer->getPost_date()->format("d-m-Y"); ?> <span class="glyphicon glyphicon-time"></span> <?php echo $answer->getPost_date()->format("H:i"); ?></small></h3>
                    </div>
                    <div class="panel-body text-justify">
                        <?php echo $answer->getContent(); ?>
                        <hr>
                        <a href="<?php echo app_host ?>/question/<?php echo $answer->getQuestion()->getId(); ?>" class="btn btn-primary pull-left"><span class="glyphicon glyphicon-zoom-in"></span> Ver en contexto</a>
                        <div class="pull-right">
                            <a href="<?php echo app_host ?>/administration/publish/comment/<?php echo $answer->getId(); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Publicar</a>
                            <a href="<?php echo app_host ?>/administration/remove/comment/<?php echo $answer->getId(); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-remove-sign"></span> Eliminar</a>                
                        </div>
                    </div>
                </div>
                <?php
            }

            if (count($unmoderated_questions) == 0 && count($unmoderated_answers) == 0) {

                echo '<div class="text-center">No hay preguntas ni comentarios pendientes a moderación.</div>';
            }
            ?>    
        </div> 

        <div class="row">
            <div class="col-xs-10 col-xs-offset-1">
                <div class="page-header">
                    <h1><small>Preguntas publicadas.</small></h1>
                </div>

            </div>
        </div>

        <div class="row col-xs-8 col-xs-offset-2">
            <?php
            $iterator = 1;
            foreach ($moderated_questions as $question) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Pregunta <?php echo $iterator++; ?>: <small class="pull-right"><span class="glyphicon glyphicon-calendar"></span> <?php echo $question->getPost_date()->format("d-m-Y"); ?> <span class="glyphicon glyphicon-time"></span> <?php echo $question->getPost_date()->format("H:i"); ?></small></h3>
                    </div>
                    <div class="panel-body text-justify">
                        <?php echo $question->getContent(); ?>
                        <hr>
                        <div class="pull-right">                    
                            <a href="<?php echo app_host ?>/administration/remove/question/<?php echo $question->getId(); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-remove-sign"></span> Eliminar</a>
                        </div>
                    </div>
                </div>
                <?php
            }

            if (count($moderated_questions) == 0) {

                echo '<div class="text-center">No hay preguntas publicadas.</div>';
            }
            ?>
        </div> 
        <?php
    } else {

        echo '<div class="text-center">No tienes permisos para administrar... Mejor dime algo...</div>';
        include 'question.php';
    }
} else {
    ?>
    <div class="col-xs-8 col-xs-offset-2 text-center">
        <div class="text-center"><h1>¡Hey, necesitas estar autenticado!</h1></div>    
        <hr>
        <form role="form" action="<?php echo app_host ?>/administration"  method="POST">
            <input name="checker" type="hidden">
            <div class="form-group">
                <input name="txt_nick" type="text" class="form-control" placeholder="¿Cómo se te identifica en el foro?" required>
            </div>
            <div class="form-group">
                <input name="txt_password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Especifica una contraseña." required>
            </div>

            <button type="submit" class="btn btn-primary pull-right">Autenticar</button>
        </form>
    </div>
    <?php
}
?>