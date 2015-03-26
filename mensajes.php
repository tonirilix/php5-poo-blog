<?php
require "includes/constants.php";
require "classHelpers/class.eyesecuresession.inc.php";
include("classes/Seguridad.php");
$miembro = new Seguridad();
$miembro->esSesionValida();

/*
 * En los momentos de crisis, sólo la imaginación es más importante que el conocimiento.
 * Albert Einstein
 */

    include("includes/webApp.Config.php");                
    header(configurations::HeaderParameter());
    echo configurations::DocType();
?>
<html>
<head>
    <title><?php echo configurations::IndexTitle(); ?></title>
    <base href="<?php echo configurations::BaseRef(); ?>"/>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo configurations::Encoding(); ?>"/>
    <meta name="Keywords" content="<?php echo configurations::KeyWords(); ?>"/>
    <meta name="Description" content="<?php echo configurations::PageDescription(); ?>"/>
    
    <script src="jScripts/jquery.js" type="text/javascript"></script>
    
    <link rel="stylesheet" type="text/css" href="css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo configurations::CssFilePath(); ?>"/>    
    <link rel="stylesheet" type="text/css" href="css/responsive.css"/>
    <link rel="stylesheet" type="text/css" href="jScripts/tooltip/tooltip.css"/>
    <link rel="stylesheet" type="text/css" href="jScripts/tooltip/tooltip_002.css"/>    
    <script src="jScripts/tooltip/tooltip.js" type="text/javascript"></script>
    <script src="jScripts/select.js" type="text/javascript"></script>
    <script src="jScripts/hb.functions.js" type="text/javascript"></script>
    <script src="jScripts/main.js" type="text/javascript"></script>    
    <script src="jScripts/autoResize.js" type="text/javascript"></script>    
    <script src="jScripts/hb.wall.functions.js" type="text/javascript"></script>    
    <script>
        $(document).ready(function() {
            hbwkWall.init();
        });
    </script>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo configurations::IconFilePath(); ?>" />                
</head>
    <body>        
        <?php include("phpSections/popup.php"); ?>        
        <?php include("phpSections/header.php"); ?>
        <div class="contenedor clearfix">            
            <?php
                include('phpSections/menu.php');
            ?>            
            <div id="content">
                <div id="content-wrapper">
                  <h2 id="add-new-product">Albumes</h2>                   
                  <div class="data" id="comentarios">
                            <span class="head">Comentarios</span>
                            <div class="table">
                                    <dl>
                                        <form method="post" action="api/apiPublicacion.php?action=AgregarComentarioAAlbum" id="message_wall_form">
                                            <input type="hidden" name="Album" value="1" />
                                            <textarea id="MainPublicationBox" style="min-height: 50px" name="Comentario"></textarea>                                    
                                            <input style="float: right; border: none;" type="button" id="submit_button_wall" onclick="" class="button white" value="Publicar" />                            
                                        </form>
                                    </dl>
                                <div id="XDXD">
                                    <?php                                
                                        require "classes/DB.php";
                                        require "classes/Publicacion.php";
                                        DB::init($dbOptions);
                                        $comentarios = Publicacion::ObtenerComentariosAlbum(1);
                                        for($i = 0;$i<sizeof($comentarios);$i++){                                    
                                    ?>
                                    <dl>
                                        <dt><?php echo $comentarios[$i]->username ?>: </dt>
                                        <?php echo $comentarios[$i]->comentario; ?>
                                    </dl>
                                    <?php } ?>                                    
                                </div>
                            </div>
                    </div>                                                   
                </div>
            </div>            
        </div>
    </body>
</html>

