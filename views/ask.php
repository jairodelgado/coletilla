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
            ¡Quiero proponer un tema para su publicación!                   
        </h3>  
    </div>
</div>
<hr>
<?php
if (isset($_POST["checker"])) {
    if (isset($_POST["txt_question"]) && $_POST["txt_question"] != "") {

        $question = new question($_POST["txt_question"], false, new \DateTime("now"));
        $db_manager->persist($question);
        $db_manager->flush();
        echo '<div class="text-center">Pues ya está, tu pregunta esta esperando por moderación. ¡Pronto será publicada!<br></div>';
    } else {

        echo '<div class="text-center">Upsss... completa los campos en blanco para continuar.<br></div>';
    }
}
?>
<div class="row col-xs-8 col-xs-offset-2">    
    <form role="form" action="<?php echo app_host ?>/ask" method="POST">  
        <input name="checker" type="hidden">
        <div class="form-group">
            <textarea name="txt_question" class="form-control" style="resize: vertical;" rows="4" placeholder="Escriba su pregunta aquí." required></textarea>
            <p class="help-block">Nota: El grupo editorial del foro se reserva el derecho de publicar su pregunta y no se hace responsable por su contenido.</p>
        </div>
        <button type="submit" class="btn btn-primary pull-right">¡Preguntar!</button>
    </form>
</div> 
