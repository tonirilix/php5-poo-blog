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
                  <h2 id="registered-users">Usuarios Registrados</h2>
                  <div id="welcome">
                        <p>El listado de abajo muestra los usuarios del sistema y proximos a activar.</p>
                  </div>
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

                        <span class="head">Usuarios</span>
                        <div class="table">
                            <?php                                                                
                                require "classes/Administrar.php";                                
                                $usuarios = Administrar::ObtenerTodosLosUsuarios();
                                for($i = 0;$i<sizeof($usuarios);$i++){                                    
                            ?>
                            <dl>
                                <dt><img alt="Avatar" src="<?php echo $usuarios[$i]->Avatar!=""?$usuarios[$i]->Avatar:"media/avatars/default-avatar.gif"; ?>">
                                    <?php echo $usuarios[$i]->Nick!=""?$usuarios[$i]->Nick:"Usuario"; ?>&nbsp;|&nbsp;
                                </dt>
                                <code><?php echo $usuarios[$i]->Email; ?></code>
                                <dd>
                                    <?php if(!$usuarios[$i]->Aprovado){ ?>
                                    <a class="tooltip" style=" cursor: pointer;" id="<?php echo $usuarios[$i]->UserId; ?>" onclick="$.aprovarUsuario(this.id,'<?php echo $usuarios[$i]->Email; ?>')" original-title="Aprobar Usuario"><img width="16" height="16" alt="Twitter" src="media/icons/main/twitter.png"></a>
                                    <?php } ?>
                                    &nbsp;&nbsp;&nbsp;
                                    <?php if(!$usuarios[$i]->Bloqueado && $usuarios[$i]->UserId != $usuarioActual->UserId){ ?>
                                    <a class="tooltip" style=" cursor: pointer;"  id="<?php echo $usuarios[$i]->UserId; ?>" onclick="$.banearUsuario(this.id,true)" original-title="Bloquear Usuario"><img width="8" height="8" alt="Bloquear" src="media/icons/nav/error-pages-on.png"></a>
                                    <?php }
                                    else
                                        if($usuarios[$i]->Bloqueado){
                                    ?>
                                    <a class="tooltip" style=" cursor: pointer;"  id="<?php echo $usuarios[$i]->UserId; ?>" onclick="$.banearUsuario(this.id,false)" original-title="Desbloquear Usuario"><img width="10" height="10" alt="Desbloquear" src="media/icons/nav/form-elements-on.png"></a>
                                    <?php
                                        }
                                    ?>
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

