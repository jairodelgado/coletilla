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

$questions = array_values($db_manager->getRepository("question")->findBy(array("published" => "true"), array("post_date" => "DESC")));
$cant = count($questions);

if ($cant == 0) {

    echo '<div class="text-center">Lo siento pero todavía no publico ninguna pregunta.</div>';
} else {

    if (isset($_GET["question"])) {

        $requested_question = $db_manager->getRepository("question")->find(intval($_GET["question"]));
        
        if(!isset($requested_question)) {
            
            $requested_question = $questions[0];
        }
    } else {

        $requested_question = $questions[0];
    }
    
    ?>
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 text-center">
            <h3>
                <?php echo $requested_question->getContent(); ?>          
            </h3>
            <ul class="pagination">
            <?php
            $i = 1;
                foreach ($questions as $question) {
                    
                    if($question->getId() == $requested_question->getId()){
                        
                        echo '<li class="active"><a href="' . app_host .'/question/' . $question->getId() . '">' .$i++ .'</a></li>';
                    } else {
                        
                        echo '<li><a href="' . app_host . '/question/' . $question->getId() . '">' .$i++ .'</a></li>';
                    }
                }
            ?>
            </ul>
        </div>
    </div>
    <hr>
    <div class="row col-xs-8 col-xs-offset-2">
        <?php
        $answers = $db_manager->getRepository("answer")->findBy(array("published" => "true", "question" => $requested_question->getId()), array("post_date" => "ASC"));

        if (count($answers) == 0) {

            echo '<div class="text-center">Todavía no se han publicado respuestas.<br><b>¡Qué esperas para ser el primero!</b></div>';
        } else {

            foreach ($answers as $answer) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $answer->getUser()->getNick_name(); ?> dijo: <small class="pull-right"><span class="glyphicon glyphicon-calendar"></span> <?php echo $answer->getPost_date()->format("d-m-Y"); ?> <span class="glyphicon glyphicon-time"></span> <?php echo $answer->getPost_date()->format("H:i"); ?></small></h3>
                    </div>
                    <div class="panel-body text-justify">
                        <?php echo $answer->getContent(); ?>
                    </div>
                </div>
                <?php
            }
        }

        echo '<br>';

        if (isset($_POST["checker"])) {

            if (isset($_SESSION["logged"])) {

                if (isset($_POST["txt_text"]) && !empty($_POST["txt_text"])) {

                    $name = $_SESSION["user"]->getNick_name();
                    $text = strip_tags($_POST["txt_text"]);
                    
                    $user = $db_manager->merge($_SESSION["user"]);
                    $answer = new answer($text, false, new \DateTime("now"), $user, $requested_question);                                      
                    $requested_question->getAnswers()->add($answer);
                    $db_manager->persist($answer);
                    $db_manager->persist($requested_question);                        

                    $db_manager->flush();
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $name; ?> dijo: <small class="pull-right"><span class="glyphicon glyphicon-screenshot"></span> Esperando moderación...</small></h3>
                        </div>
                        <div class="panel-body text-justify">
                            <?php echo $text; ?>
                        </div>
                    </div>
                    <?php
                } else {

                    echo '<div class="text-center">Upss... completa los campos en blanco para continuar.</div>';
                }
            } else {

                if (isset($_POST["txt_text"]) && !empty($_POST["txt_text"]) && isset($_POST["txt_nick"]) && !empty($_POST["txt_nick"]) && isset($_POST["txt_password"]) && !empty($_POST["txt_password"])) {

                    $name = $_POST["txt_nick"];
                    $password = $_POST["txt_password"];
                    $text = strip_tags($_POST["txt_text"]);

                    $user = $db_manager->getRepository("user")->findOneBy(array("nick_name" => "$name"));
                    if (isset($user)) {
                        if ($user->getSecret() == $password) {

                            $answer = new answer($text, false, new \DateTime("now"), $user, $requested_question);
                            $requested_question->getAnswers()->add($answer);
                            $db_manager->persist($answer);
                            $db_manager->persist($requested_question);
                            $db_manager->flush();

                            $_SESSION["logged"] = true;
                            $_SESSION["user"] = $user;
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php echo $name; ?> dijo: <small class="pull-right"><span class="glyphicon glyphicon-screenshot"></span> Esperando moderación...</small></h3>
                                </div>
                                <div class="panel-body text-justify">
                                    <?php echo $text; ?>
                                </div>
                            </div>
                            <?php
                        } else {

                            echo '<div class="text-center">Parece que te has equivocado de usuario o contraseña, trata de nuevo.</div>';
                        }
                    } else {

                        $user = new user($name, $password, "", false);
                        $db_manager->persist($user);
                        $answer = new answer($text, false, new \DateTime("now"), $user, $requested_question);
                        $requested_question->getAnswers()->add($answer);
                        $db_manager->persist($answer);
                        $db_manager->persist($requested_question);
                        $db_manager->flush();

                        $_SESSION["logged"] = true;
                        $_SESSION["user"] = $user;
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo $name; ?> dijo: <small class="pull-right"><span class="glyphicon glyphicon-screenshot"></span> Esperando moderación...</small></h3>
                            </div>
                            <div class="panel-body text-justify ">
                                <?php echo $text; ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {

                    echo '<div class="text-center">Upsss... completa los campos en blanco para continuar.</div>';
                }
            }
        }
        ?>
        <hr>
        <form id="comments" role="form" action="<?php echo app_host ?>/question/<?php echo $requested_question->getId() ?>#coments"  method="POST">
            <input name="checker" type="hidden">
            <?php
            if (!isset($_SESSION["logged"])) {
                ?>
                <div class="form-group">
                    <input name="txt_nick" type="text" class="form-control" placeholder="¿Cómo deseas que te identifiquen?" required>
                </div>
                <div class="form-group">
                    <input name="txt_password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Especifica una contraseña." required>
                    <p class="help-block">Nota: Si es la primera vez que utilizas nuestro foro serás registrado automáticamente.</p>
                </div>
                <?php
            } else {
                echo '<div class="text-right">Autenticado como: ' . $_SESSION["user"]->getNick_name() . '. <a href="' . app_host . '/logout">Salir.</a></div>';
            }
            ?>
            <div class="form-group">
                <textarea name="txt_text" class="form-control" style="resize: vertical;" rows="4" placeholder="Escriba su respuesta aquí." required></textarea>
                <p class="help-block">Nota: El grupo editorial del foro se reserva el derecho de publicar su comentario y no se hace responsable por su contenido.</p>
            </div>
            <button type="submit" class="btn btn-primary pull-right">¡Comentar!</button>
        </form>
    </div> 
    <?php
}
?>

