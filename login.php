<?php
/*require "includes/constants.php";
require "classHelpers/class.eyesecuresession.inc.php";
include("classes/Seguridad.php");
$miembro = new Seguridad();
if($miembro->esSesionValida()){    
    //header('Location: index.php');
}*/

/*
 * En los momentos de crisis, sólo la imaginación es más importante que el conocimiento.
 * Albert Einstein
 */
    session_start();
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
    <link rel="stylesheet" type="text/css" href="css/login.css"/>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo configurations::IconFilePath(); ?>" />        
    <script src="jScripts/jquery.js" type="text/javascript"></script>
    <script src="jScripts/hb.functions.js" type="text/javascript"></script>
    <script src="jScripts/main.js" type="text/javascript"></script>        
</head>
    <body>
        <?php include("phpSections/popup.php"); ?>        
        <?php include("phpSections/header.php"); ?>
        <div class="contenedor clearfix">            
            <div id="login">
                <div class="data" id="login-page">
                    <span class="head">
                            <a class="register" href="registro.php">Registro</a>
                            Por favor, inicie sesion.
                    </span>
                    <div class="table">                                                
                        <p id="please-login">Por favor, inicie sesion para continuar.</p>                                                    
                        <div class="notes login-content">
                            <form id="IniciarSesion" method="post" action="#">
                                <div class="input login-input login-input-username">
                                    <input type="text" id="Email" name="Email" class="defaultText" value="" placeholder="Email" required="required">
                                </div>
                                <div class="input login-input login-input-password">
                                    <input type="password" id="Password" name="Password" value="" placeholder="Contraseña" required="required">
                                </div>
                                <div id="infologin" class="box warning" style="display: none">
                                    <p>Iniciando sesion. Por favor espere...</p>                                    
                                </div>
                                <div class="lost-password" style="display: none;">
                                    <a href="#">&iquest;Olvidaste tu contrase&ntilde;a?</a>
                                </div>
                                <div class="login-button">
                                    <input type="button" id="btnLogin" onclick="$.IniciarSesion()" class="button orange" value="Entrar" />                                    
                                </div>
                                <div class="clear"></div>
                            </form>
                        </div>                        
                    </div>
                </div>                                                
                <!-- FOOTER -->
                <div class="clearfix" id="mini-footer">
                     <p><img width="40%" height="40%" alt="ESR" src="images/footer_esr.png"></p>
                </div>
                <!-- //FOOTER -->

            </div>
        </div>        
    </body>    
</html>

