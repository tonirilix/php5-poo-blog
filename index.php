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
    <link rel="stylesheet" type="text/css" href="css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo configurations::CssFilePath(); ?>"/>    
    <link rel="stylesheet" type="text/css" href="css/responsive.css"/>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo configurations::IconFilePath(); ?>" />        
    <script src="jScripts/jquery.js" type="text/javascript"></script>
    <script src="jScripts/hb.wall.functions.js" type="text/javascript"></script>
    <script src="jScripts/hb.functions.js" type="text/javascript"></script>
    <script src="jScripts/main.js" type="text/javascript"></script>    
    <script>
        $.tzPOST = function(){
            $.post('api/apiMiembro.php?action=CrearUsuario',{Email:'mofles91@hotmail.com',UserName:'Antonio',Password:"##cagada##89"},function(data){
                alert(data);
            },'json');
        }                
    </script>
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
                    <h2 id="dashboard">Inicio</h2>                    
                </div>
            </div>    
            
            
            <?php                                                            
                require "classes/Publicacion.php";
                //DB::init($dbOptions);
                $albumes = Publicacion::ObtenerAlbumes();
                for($i = 0;$i<sizeof($albumes);$i++){                                    
            ?>
            <div id="content" style="margin-top: 20px;">
                <div id="content-wrapper">
                  <h2 id="icon-gallery"><a href="Album.php?Album=<?php echo $albumes[$i]->id; ?>"><?php echo $albumes[$i]->nombre!=""?$albumes[$i]->nombre:"Album"; ?></a></h2>
                  <div id="welcome">
                        <p><?php echo $albumes[$i]->descripcion ?>.</p>
                  </div>
                  <?php if($albumes[$i]->img_id==""){ ?>
                  <div id="infologin" class="box warning">
                    <p>No hay imagenes</p>                                    
                  </div>
                  <?php } else{?>
                  <div class="data" id="gallery">                                                                                                  
                        <ul class="clearfix">
                            <li id="Imagen_<?php echo $albumes[$i]->img_id; ?>">                                
                                <img src="<?php echo $albumes[$i]->img_path.$albumes[$i]->img_nombre."_thumbnail.".$albumes[$i]->img_ext; ?>" alt="<?php echo $albumes[$i]->description; ?>" style="margin:0 auto;" />                        
                                <div class="gfooter"><?php echo $albumes[$i]->img_fecha_creacion; ?></div>
                            </li>
                        </ul>                      
                    </div>
                  <?php }?>
                </div>
            </div>
            <?php }?>
            
        </div>
    </body>
</html>

