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
?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1">
        <h3 class="text-center">
            ¡Quiero que me notifiquen cuando surgan nuevas coletillas!                   
        </h3>  
    </div>
</div>
<hr>
<?php
if (isset($_POST["checker"])) {

    if (isset($_SESSION["logged"])) {

        if (isset($_POST["txt_jabber_id"]) && !empty($_POST["txt_jabber_id"])) {

            $name = $_SESSION["user"]->getNick_name();
            $jabber_id = $_POST["txt_jabber_id"];

            $user = $db_manager->merge($_SESSION["user"]);
            $user->setEmail($jabber_id);
            $db_manager->persist($user);
            $db_manager->flush();

            echo '<div class="text-center">¡Enhorabuena, ahora recibirás notificaciones vía Jabber!.<br></div>';
        } else {

            echo '<div class="text-center">Upsss... completa los campos en blanco para continuar.<br></div>';
        }
    } else {

        if (isset($_POST["txt_jabber_id"]) && !empty($_POST["txt_jabber_id"]) && isset($_POST["txt_nick"]) && !empty($_POST["txt_nick"]) && isset($_POST["txt_password"]) && !empty($_POST["txt_password"])) {

            $name = $_POST["txt_nick"];
            $password = $_POST["txt_password"];
            $jabber_id = $_POST["txt_jabber_id"];

            $user = $db_manager->getRepository("user")->findOneBy(array("nick_name" => "$name"));
            if (isset($user)) {
                if ($user->getSecret() == $password) {

                    $user->setEmail($jabber_id);
                    $db_manager->persist($user);
                    $db_manager->flush();

                    $db_manager->flush();

                    $_SESSION["logged"] = true;
                    $_SESSION["user"] = $user;

                    echo '<div class="text-center">¡Enhorabuena, ahora recibirás notificaciones vía Jabber!.<br></div>';
                } else {

                    echo '<div class="text-center">Parece que te has equivocado de usuario o contraseña, trata de nuevo.<br></div>';
                }
            } else {

                $user = new user($name, $password, $jabber_id, false);
                $db_manager->persist($user);
                $db_manager->flush();

                $_SESSION["logged"] = true;
                $_SESSION["user"] = $user;

                echo '<div class="text-center">¡Enhorabuena, ahora recibirás notificaciones vía Jabber!.<br></div>';
            }
        } else {

            echo '<div class="text-center">Upsss... completa los campos en blanco para continuar.<br></div>';
        }
    }
}
?>
<div class="row col-xs-8 col-xs-offset-2">

    <form role="form" action="<?php echo app_host ?>/suscription"  method="POST">        
        <input name="checker" type="hidden">
        <?php
        if (!isset($_SESSION["logged"])) {
            ?>
            <div class="form-group">
                <input name="txt_nick" type="text" class="form-control" placeholder="¿Cómo deseas que te identifiquen?" required>
            </div>
            <div class="form-group">
                <input name="txt_password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Especifica tu contraseña." required>
                <p class="help-block">Nota: Si es la primera vez que va utilizas nuestro foro serás registrado automáticamente.</p>
            </div>
            <?php
        } else {
            echo '<div class="text-right">Autenticado como: ' . $_SESSION["user"]->getNick_name() . '. <a href="index.php?view=logout">Salir.</a></div>';
        }
        ?>
        <div class="form-group">
            <input name="txt_jabber_id" type="email" class="form-control" placeholder="Escribe tu identificador de Jabber. Ej. john@jabber.uci.cu" required>
            <p class="help-block">Nota: Recibirás notificaciones vía Jabber.</p>
        </div>
        <button type="submit" class="btn btn-primary pull-right">¡Suscribir!</button>
    </form>
</div> 
