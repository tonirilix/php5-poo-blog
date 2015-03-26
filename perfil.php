<?php
require "includes/constants.php";
require "DataModel/UsuarioDataModel.php";
require "classes/DB.php";
DB::init($dbOptions);
require "classHelpers/class.eyesecuresession.inc.php";
$GLOBAL_session = new EyeSecureSession(SESSIONPASS);                       
include("classes/Seguridad.php");
$seguridad = new Seguridad();
$seguridad->esSesionValida($GLOBAL_session);

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
    <script src="jScripts/main.js" type="text/javascript"></script>    
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
                  <h2 id="add-new-product">Perfil</h2>
                                                                                                                             
                  <div class="data" id="cambiarNombre">
                      <span class="head">Cambiar Nombre</span>
                        <form method="post" action="" id="FormCNombre">
                            <div class="table">
                                <dl>
                                    <dt><label for="Nombre">Nombre</label></dt>
                                    <dd><input type="text" class="input form text" id="Nombre" name="Nombre" required="required" value="<?php  echo $usuarioActual->UserName; ?>"></dd>
                                </dl>                                                                
                            </div>
                            <input type="button" id="btnCrearAlbum" onclick="$.CambiarNombre();" class="button orange" value="Cambiar Nombre" />
                        </form>
                    </div>   
                  
                  <div style="margin-top: 20px" id="welcome">
                        <p></p>
                  </div>
                  
                   <?php
                                  // we first include the upload class, as we will need it here to deal with the uploaded file
                    include('classes/Upload.php');

                    // retrieve eventual CLI parameters
                    $cli = (isset($argc) && $argc > 1);
                    if ($cli) {
                        if (isset($argv[1])) $_GET['file'] = $argv[1];
                        if (isset($argv[2])) $_GET['dir'] = $argv[2];
                        if (isset($argv[3])) $_GET['pics'] = $argv[3];
                    }

                    // set variables
                    $dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'media/avatars');
                    $dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);

                    if (!$cli && !$_POST) {
                    ?>      
                  
                  <div class="data" id="statistics">
                      <span class="head">Agregar Imagen</span>
                      <form name="form1" enctype="multipart/form-data" method="post" action="perfil.php">
                          <input type="hidden" name="action" value="image" />                          
                          <div class="table">                                                            
                              <dl>
                                  <dt><label for="imagen">Imagen Actual</label></dt>
                                  <dt><img src="<?php echo $usuarioActual->Avatar; ?>" /></dt>                                  
                              </dl> 
                               <dl>
                                  <dt><label for="imagen">Imagen</label></dt>                                  
                                  <dd><input type="file" id="image" class="input form text" name="my_field"></dd>
                              </dl> 
                          </div>
                          <input type="submit" class="button orange" value="Cambiar Imagen" />
                      </form>                         
                                                      
                  </div>
                  <?php }
      
                      if ((isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '')) == 'image') {

                    // ---------- IMAGE UPLOAD ----------

                    // we create an instance of the class, giving as argument the PHP object
                    // corresponding to the file field from the form
                    // All the uploads are accessible from the PHP object $_FILES
                    $handle = new Upload($_FILES['my_field']);

                    // then we check if the file has been uploaded properly
                    // in its *temporary* location in the server (often, it is /tmp)
                    if ($handle->uploaded) {

                        // yes, the file is on the server
                        // below are some example settings which can be used if the uploaded file is an image.
                        /*$handle->image_resize            = true;
                        $handle->image_ratio_y           = true;
                        $handle->image_x                 = 300;*/
                        $nombre = uniqid('', true);
                        $handle->file_new_name_body = $nombre;                        

                        // now, we start the upload 'process'. That is, to copy the uploaded file
                        // from its temporary location to the wanted location
                        // It could be something like $handle->Process('/home/www/my_uploads/');
                        $handle->Process($dir_dest);
                        $error = 0;
                        // we check if everything went OK
                        if ($handle->processed) {   
                            // everything was fine !
                            echo '<fieldset>';
                            echo '  <legend>Imagen subida con exito</legend>';
                            //echo '  <img src="'.$dir_pics.'/' . $handle->file_dst_name . '" />';
                            
                            $nombreBase = $handle->file_dst_name_body;
                            $nombreBaseExt = $handle->file_dst_name_ext;
                            
                            $info = getimagesize($handle->file_dst_pathname);
                            
                            //echo '  <p>' . $info['mime'] . ' &nbsp;-&nbsp; ' . $info[0] . ' x ' . $info[1] .' &nbsp;-&nbsp; ' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB</p>';
                            //echo '  link to the file just uploaded: <a href="'.$dir_pics.'/' . $handle->file_dst_name . '">' . $handle->file_dst_name . '</a><br/>';
                            echo '</fieldset>';
                        } else {
                            $error = 1;
                            // one error occured
                            echo '<fieldset>';
                            echo '  <legend>La imagen no pudo ser transferida</legend>';
                            echo '  Error: ' . $handle->error . '';
                            echo '</fieldset>';
                        }
                        
                        if(!$error){
                            // we now process the image a second time, with some other settings
                            $handle->image_resize            = true;
                            $handle->image_ratio_y           = true;
                            $handle->image_x                 = 20;
                            $handle->file_new_name_body = $nombre."_t";
                            /*$handle->image_reflection_height = '25%';
                            $handle->image_contrast          = 50;*/

                            $handle->Process($dir_dest);

                            // we check if everything went OK
                            if ($handle->processed) {
                                // everything was fine !
                                //echo '<fieldset>';
                                //echo '  <legend>file uploaded with success</legend>';
                                //echo '  <img src="'.$dir_pics.'/' . $handle->file_dst_name . '" />';
                                $info = getimagesize($handle->file_dst_pathname);
                                //echo '  <p>' . $info['mime'] . ' &nbsp;-&nbsp; ' . $info[0] . ' x ' . $info[1] .' &nbsp;-&nbsp; ' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB</p>';
                                //echo '  link to the file just uploaded: <a href="'.$dir_pics.'/' . $handle->file_dst_name . '">' . $handle->file_dst_name . '</a><br/>';
                                //echo '</fieldset>';
                            } else {
                                // one error occured
                                echo '<fieldset>';
                                echo '  <legend>file not uploaded to the wanted location</legend>';
                                echo '  Error: ' . $handle->error . '';
                                echo '</fieldset>';
                            }

                            // we delete the temporary files
                            $handle-> Clean();

                        } else {
                            $error=1;
                            // if we're here, the upload file failed for some reasons
                            // i.e. the server didn't receive the file
                            //echo '<fieldset>';
                            //echo '  <legend>file not uploaded on the server</legend>';
                            //echo '  Error: ' . $handle->error . '';
                            //echo '</fieldset>';
                        }
                        
                        if(!$error){                                                                                                                
                            require 'classes/Miembro.php';
                            //$comentarios = Publicacion::AgregarImagen($_POST['Titulo'],$_POST['Descripcion'], $_POST['Album'], $dir_pics.'/', $nombreBase,$nombreBaseExt,$GLOBAL_session);
                            $Avatar = $dir_pics.'/'.$nombreBase."_t.".$nombreBaseExt;
                            $imagen = Miembro::CambiarImagen($Avatar,$GLOBAL_session);
                        }
                        
                    }
                    else{
                        echo '<fieldset>';
                        echo '  <legend>Ocurrio un error al subir la imagen.</legend>';
                        echo '  Error: ' . $handle->error . '';
                        echo '</fieldset>';
                    }
                    echo "<h1>Redirigiendo... Espere.</h1>";
                    echo "<script>setTimeout(\"window.location = 'perfil.php'\",3000);</script>";
                  }
                  ?>
                  <div style="margin-top: 20px" id="welcome">
                        <p></p>
                  </div>
                  
                  <?php
                    if(!$seguridad->validarCorreo($usuarioActual->Email)){
                  ?>
                  <div class="data" id="cambiarContasenia">
                      <span class="head">Cambiar Contrase&ntilde;a</span>
                        <form method="post" action="" id="FormCContrasenia">
                            <div class="table">
                                <dl style="display: none">
                                    <dt><label for="Nombre">Contrase&ntilde;a Anterior</label></dt>
                                    <dd><input type="text" class="input form text" id="Password_old" name="Password_old" value="XD" required="required"></dd>
                                </dl>                                
                                <dl>
                                    <dt><label for="Nombre">Nueva Contrase&ntilde;a</label></dt>
                                    <dd><input type="password" class="input form text" id="Password_new" name="Password_new" required="required"></dd>
                                </dl>                                
                                <dl>
                                    <dt><label for="Nombre">Confirmar contrase&ntilde;a</label></dt>
                                    <dd><input type="password" class="input form text" id="Password_new_confirm" name="Password_new_confirm" required="required"></dd>
                                </dl>                                
                            </div>
                            <input type="button" id="btnCrearAlbum" onclick="$.CambiarPassword();" class="button orange" value="Cambiar Contrase&ntilde;a" />
                        </form>
                    </div> 
                  <?php }?>
                </div>
            </div>            
        </div>
    </body>
</html>

