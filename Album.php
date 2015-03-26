<?php
require "includes/constants.php";
require "DataModel/UsuarioDataModel.php";
require "classes/DB.php";
DB::init($dbOptions);
require "classHelpers/class.eyesecuresession.inc.php";
$GLOBAL_session = new EyeSecureSession(SESSIONPASS);                       
include("classes/Seguridad.php");
$miembro = new Seguridad();
$miembro->esSesionValida($GLOBAL_session);

require "classes/Rol.php";
require "classes/CustomAuthorize.php";
$usuarioActual = @$GLOBAL_session->get('UsuarioActual');
$AccesoRestringido = new OnlyAdministradorAuthorizeAttribute($usuarioActual->Email, "Administrador",true);

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
    <script src="jScripts/autoResize.js" type="text/javascript"></script>        
    <script src="jScripts/hb.wall.functions.js" type="text/javascript"></script>
    <script src="jScripts/main.js" type="text/javascript"></script>        
    
    <link rel="stylesheet" href="jScripts/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
    <script type="text/javascript" src="jScripts/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    
    <!-- POPUPBOX -->
    <link rel="stylesheet" type="text/css" href="jScripts/popupbox/popupbox.css"/>    
    <script src="jScripts/popupbox/popupbox.js" type="text/javascript"></script>
    
    <script type="text/javascript" language="javascript">
        $(document).ready(function() {
            hbwkWall.data.auth = <?php echo $AccesoRestringido->resultado; ?>;
            hbwkWall.init();                                                
        });
    </script>                        
    
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo configurations::IconFilePath(); ?>" />                
</head>
    <body>
        <div id="notifications"></div>
        <?php include("phpSections/popup.php"); ?>        
        <?php include("phpSections/header.php"); ?>
        <div class="contenedor clearfix">            
            <?php
                include('phpSections/menu.php');                                 
                require "classes/Publicacion.php";                 
                 $IdAlbum = $_GET['Album'];
                 
                 $album = Publicacion::ObtenerAlbum($IdAlbum);
                  
            ?>            
            <div id="content">
                <div id="content-wrapper">
                  <h2 id="icon-gallery"><?php echo $album['Nombre']?></h2>
                  <div id="welcome">
                        <p><?php echo $album['Descripcion']?>.</p>
                  </div>
                  <div class="data" id="gallery">

                      <a href="AgregarImagen.php?Album=<?php echo $IdAlbum?>" class="button orange">Agregar Imagen</a>
                        <br><br>
                        <!-- PAGINATION -->
                        <nav class="pagination" style="display: none">
                                <a class="direction" href="#">Prev</a>
                                <a href="#">1</a>
                                <a href="#">2</a>
                                <a href="#">3</a>
                                <a href="#">4</a>
                                <a href="#">...</a>
                                <a class="direction" href="#">Next</a>
                        </nav>
                        <!-- //PAGINATION -->
                        <script type="text/javascript" language="javascript">
                            $(document).ready(function() {
                                $.ObtenerImagenesDeAlbum(<?php echo $IdAlbum?>,<?php echo $AccesoRestringido->resultado; ?>);                                
                            });
                        </script>                        
                        <ul class="clearfix" id="listImagenes">
                            
                        </ul>
                      
                </div>
                <div class="data" id="comentarios">
                            <span class="head">Comentarios</span>
                            <div class="table">
                                    <dl>
                                        <form method="post" action="api/apiPublicacion.php?action=AgregarComentarioAAlbum" id="message_wall_form">
                                            <input type="hidden" name="Album" value="<?php echo $IdAlbum?>" />
                                            <textarea id="MainPublicationBox" style="min-height: 50px" name="Comentario"></textarea>                                    
                                            <input style="float: right; border: none;" type="button" id="submit_button_wall" onclick="" class="button white" value="Publicar" />                            
                                        </form>
                                    </dl>
                                <script type="text/javascript" language="javascript">
                                    $(document).ready(function() {
                                        $.ObtenerComentariosDeAlbum(<?php echo $IdAlbum?>,<?php echo $AccesoRestringido->resultado; ?>,false);                                
                                    });
                                </script>                        
                                <div id="listComentarios">
                                    
                                </div>
                            </div>
                    </div>    
                    </div>
                </div>
            </div>            
        </div>
    </body>
</html>

