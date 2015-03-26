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
$AccesoRestringido = new OnlyAdministradorAuthorizeAttribute($usuarioActual->Email, "Administrador");


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
    
    <!-- JQUERY LIBRARIES -->
    
    <!-- TOOTIP -->
    <link rel="stylesheet" type="text/css" href="jScripts/tooltip/tooltip.css"/>
    <link rel="stylesheet" type="text/css" href="jScripts/tooltip/tooltip_002.css"/>    
    <script src="jScripts/tooltip/tooltip.js" type="text/javascript"></script>
    
    <!-- POPUPBOX -->
    <link rel="stylesheet" type="text/css" href="jScripts/popupbox/popupbox.css"/>    
    <script src="jScripts/popupbox/popupbox.js" type="text/javascript"></script>
                    
    <!-- GLOBAL LIBRARIES -->
    <script src="jScripts/select.js" type="text/javascript"></script>    
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo configurations::IconFilePath(); ?>" />                        
    
    <script src="jScripts/hb.functions.js" type="text/javascript"></script>
    <script src="jScripts/main.js" type="text/javascript"></script>    
    
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
                  
                  <div class="data" id="addAlbum" style="display:none">
                      <span class="head">Informaci&oacute;n requerida de album</span>
                        <form method="post" action="" id="Form">
                            <input type="hidden" id="IdAlbum" name="IdAlbum" value="0" />
                            <div class="table">
                                <dl>
                                    <dt><label for="Nombre">Nombre del Album</label></dt>
                                    <dd><input type="text" class="input form text" id="Nombre" name="Nombre" required="required"></dd>
                                </dl>
                                <dl>
                                    <dt><label for="Descripcion">Descripcion</label></dt>
                                    <textarea id="Descripcion" name="Descripcion"></textarea>                                    
                                </dl>                                    
                            </div>
                            <input type="button" id="btnCrearAlbum" onclick="$.CrearAlbum();" class="button orange" value="Guardar Album" />
                            <input type="button" id="btnCrearAlbum" onclick="$('#addAlbum').fadeOut(250);" class="button orange" value="Cancelar" />
                        </form>
                    </div>
                  <div style="margin-top: 20px" id="welcome">
                        <p>Lista de albumes.</p>
                  </div>
                  <a style="cursor:pointer; margin-bottom: 20px;" onclick="$('#addAlbum').fadeIn(250); $('#IdAlbum').val('0'); $('#Form').each(function(){this.reset();});" class="button orange">Agregar Album</a>
                   <div class="data" id="users">

                        <a style="display:none" href="add-new-user.html" class="button orange right">Add New User</a>
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

                        <span class="head">Albumes</span>
                        <div class="table" id="tableAlbumes">
                            <?php                                                                
                                require "classes/Publicacion.php";
                                DB::init($dbOptions);
                                $albumes = Publicacion::ObtenerAlbumes();
                                for($i = 0;$i<sizeof($albumes);$i++){                                    
                            ?>
                            <dl id="<?php echo "AlbumContainer_".$albumes[$i]->id ?>">
                                <dt><img alt="Avatar" src="media/icons/main/album_item.gif">
                                    <?php echo $albumes[$i]->nombre." - ".$albumes[$i]->fecha_creacion; ?>
                                </dt>
                                <dd>                                    
                                    <a id="<?php echo "AlbumM_".$albumes[$i]->id ?>" onclick="$('#Nombre').val('<?php echo $albumes[$i]->nombre ?>'); $('#IdAlbum').val('<?echo $albumes[$i]->id; ?>'); $('#Descripcion').val('<?php echo $albumes[$i]->descripcion; ?>'); $('#addAlbum').show()" class="tooltip" style="cursor:pointer; border:1px solid transparent; width: 15px; height: 15px;" original-title="Cambiar nombre"><img width="8" height="8" alt="Modificar" src="media/icons/nav/gallery.png"></a>
                                    <a id="<?php echo "Album_".$albumes[$i]->id ?>" onclick="$.eliminarAlbum(this.id);" class="tooltip" style="cursor:pointer; border:1px solid transparent;width: 15px; height: 15px;" original-title="Eliminar album"><img width="8" height="8" alt="Eliminar" src="media/icons/nav/error-pages-on.png"></a>                                    
                                </dd>
                            </dl>                                    
                            <?php } ?>
                        </div>
                    </div>
                  
                </div>
            </div>            
        </div>
    </body>
</html>

