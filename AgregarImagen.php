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
    <script src="jScripts/main.js" type="text/javascript"></script>    
        
    <script src="jScripts/j.validate/jquery.validate.min.js" type="text/javascript"></script>
    
    <style type="text/css">
        .error{
             color: #a5731c;
             font-size: 12px;
             font-weight: bold;
            /*display: inline;
            /float: left;                        
            margin: 5px 0;
            text-align: center;
            width: auto;*/
        }   
        label.error{
            display:block;
        }
    </style>
    
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo configurations::IconFilePath(); ?>" />                
    <script>
        $(document).ready(function() {            
  
            $("#Form1").validate({
                rules: {
                    Titulo:{
                        required:true,                                    
                        minlength : 2
                    },
                    Descripcion:{
                        required:true,                                    
                        minlength:2                                    
                    },
                    my_field:{
                        required:true
                    }                            
                },
                messages: {
                    Titulo:{ 
                        required: "Campo requerido",
                        number:"Ingrese un titulo.",
                        minlength: "Debe ser de mayor a 2 digitos"
                    },
                    Descripcion: {
                        required: "Campo requerido",
                        number:"Ingrese una descripcion valida",
                        minlength: "Debe ser mayor a 2 digitos"                                    
                    },
                    my_field:{
                        required:"Campo Requerido"
                    }                            
                },
                submitHandler: function(form) {    
                    NOTIFICATIONS.displayNotification("Espere un momento. Publicando...", true);
                    form.submit();                            
                }
            });
            
        });
    </script>
</head>
    <body>
        <div id="notifications"></div>
        <?php include("phpSections/popup.php"); ?>        
        <?php include("phpSections/header.php"); ?>
        <div class="contenedor clearfix">            
            <?php
                include('phpSections/menu.php');                                 
                require "classes/Publicacion.php";
                 $IdAlbum = @$_GET['Album'];
                
           ?>            
            <div id="content">
                <div id="content-wrapper">                  
                  
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
                    $dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'files_uploaded');
                    $dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);

                    if (!$cli && !$_POST) {
                    ?>                  
                  <h2 id="icon-gallery">Agregar Imagen</h2>
                  <div id="welcome">
                        <p>Aqui puedes agregar una nueva imagen al album seleccionado.</p>
                        <p>FAVOR DE LLENAR TODOS LOS CAMPOS.</p>
                  </div>
                  <div class="data" id="statistics">

                      <span class="head">Agregar Imagen</span>
                      <form name="form1" id="Form1" enctype="multipart/form-data" method="post" action="AgregarImagen.php">
                          <input type="hidden" name="action" value="image" />
                          <input type="hidden" name="Album" value="<?php echo $_GET['Album']; ?>" />
                          <div class="table">
                              <dl>
                                  <dt><label for="titulo">Titulo</label></dt>
                                  <dd><input type="text" id="Titulo" name="Titulo" autocomplete="off" class="input form text required"></dd>
                              </dl>
                              <dl>
                                  <dt><label for="descripcion">Descripcion</label></dt>
                                  <dd><textarea id="Descripcion" name="Descripcion" class="input form text required"></textarea></dd>
                              </dl> 
                               <dl>
                                  <dt><label for="imagen">Imagen</label></dt>                                  
                                  <dd><input type="file" id="my_field" class="input form text required" name="my_field"></dd>
                              </dl> 
                          </div>
                          <input type="submit" class="button orange" value="Publicar" />
                      </form>                         
                                                      
                  </div>
                  <?php }
                  
                  if ((isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '')) == 'image') {
                      
                      echo "<h2 id='icon-gallery'>Seleccione una opcion.</h2>";

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
                            echo"<div id='infologin' class='box success'>
                                    <p>";                            
                            echo 'Imagen subida con exito';
                            //echo '  <img src="'.$dir_pics.'/' . $handle->file_dst_name . '" />';
                            
                            $nombreBase = $handle->file_dst_name_body;
                            $nombreBaseExt = $handle->file_dst_name_ext;
                            
                            $info = getimagesize($handle->file_dst_pathname);
                            
                            //echo '  <p>' . $info['mime'] . ' &nbsp;-&nbsp; ' . $info[0] . ' x ' . $info[1] .' &nbsp;-&nbsp; ' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB</p>';
                            //echo '  link to the file just uploaded: <a href="'.$dir_pics.'/' . $handle->file_dst_name . '">' . $handle->file_dst_name . '</a><br/>';                            
                            echo "</p>                                    
                                    </div>";
                            echo "
                                <a href='AgregarImagen.php?Album=".$_POST['Album']."' class='button white'>Publicar otra imagen</a>
                                <a href='Album.php?Album=".$_POST['Album']."' class='button white'>Regresar al &aacute;lbum</a>
                                ";
                        } else {
                            $error = 1;
                            // one error occured
                            echo '<fieldset>';
                            echo '  <legend>
                                <div id="infologin" class="box warning">
                                <p>
                                    La imagen no pudo ser transferida.
                                </p></div>
                                </legend>';
                            //echo '  Error: ' . $handle->error . '';
                            echo '</fieldset>';
                        }
                        
                        if(!$error){
                            // we now process the image a second time, with some other settings
                            $handle->image_resize            = true;
                            $handle->image_ratio_y           = true;
                            $handle->image_x                 = 140;
                            $handle->file_new_name_body = $nombre."_thumbnail";
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
                                echo '  <legend>
                                    <div id="infologin" class="box warning">
                                    <p>
                                    El archivo no fue subido.
                                    </p>
                                    </div>
                                    </legend>';
                                //echo '  Error: ' . $handle->error . '';
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
                            try{
                            $comentarios = Publicacion::AgregarImagen($_POST['Titulo'],$_POST['Descripcion'], $_POST['Album'], $dir_pics.'/', $nombreBase,$nombreBaseExt,$GLOBAL_session,0);
                            }
                            catch(Exception $e){}
                        }
                        
                    }
                    else{
                        echo '<fieldset>';
                        echo '  <legend>
                            <div id="infologin" class="box warning">
                                <p>Ocurrio un error al subir la imagen.</p>
                            </div>
                                </legend>';
                        //echo '  Error: ' . $handle->error . '';
                        echo '</fieldset>';
                    }
                }
                  
                  ?>
                    </div>
                </div>
            </div>            
        </div>
    </body>
</html>

