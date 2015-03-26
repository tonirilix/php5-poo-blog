<?php

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
            <div style="margin-top: 20px" id="welcome">
                <h3>Si tu correo es corporativo, debes ingresar la contraseña que tiene actualmente. <br />Si tu correo es externo, la contrase&ntilde;a se te enviar&aacute; a tu correo, no es necesario ingresarla. </h3>
            </div>
            <div id="login">
                <div class="data" id="login-page">
                    <span class="head">Registro</span>
                    <div class="table">
                        <p id="please-login"><strong>Detalles de la cuenta</strong></p>
                        <div class="notes login-content">
                            <form method="post" action="" id="Form">
                                <div class="input login-input login-input-username">
                                    <input type="text" name="Username" placeholder="Nombre" required="required">
                                </div>
                                <div class="input login-input login-input-email">
                                    <input type="email" name="Email" placeholder="Email Corporativo" required="required">
                                </div>
                                <div class="input login-input login-input-password">
                                    <input type="password" name="Password" placeholder="Contrase&ntilde;a Corporativa" required="required">
                                </div>                                
                                                                
                                <div class="login-button">
                                    <input type="button" onclick="$.Registrar();" class="button orange" value="Enviar Registro" />
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

