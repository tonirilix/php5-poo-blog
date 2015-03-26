<?php
    session_start();
    include("includes/webApp.Config.php");    
    include("classes/Error.php");
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
            <div id="content">
                <div id="content-wrapper">
                  <h2 id="registered-users">Error</h2>
                  <div id="welcome">
                      <?php
                        $numeroError = "404";
                        $numeroError = @$_GET['error']? @$_GET['error']:"404";
                        $error = new Error();
                        $error->ObtenerError($numeroError);
                      ?>             
                      <p><a href="index.php">Regresar</a></p>
                  </div>                  
                </div>
            </div>  
        </div>        
    </body>    
</html>

