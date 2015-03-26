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
    <link rel="stylesheet" type="text/css" href="jScripts/tablesorter/themes/blue/style.css"/>
    <script type="text/javascript" src="jScripts/tablesorter/jquery.tablesorter.min.js"></script>
    <script>
       $(document).ready(function() 
            {
                $("#myTable").tablesorter({widgets: ['zebra']});
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
                  <h2 id="tables">Estadistica General</h2>
                  
                  <div id="chart" class="data">
                    <span class="head">Estadistica General</span>
                    <div class="table">
                        <div class="chart">
                        <div id="table_div" style="width:688px">
                        <div style="position:relative;">
                            <div style="position:relative; overflow:auto; width:688px;">                                
                            <table style="width:688px"  id="myTable" class="tablesorter">                            
                                <thead>
                                <tr>
                                <th>Imagen</th>
                                <th>Album</th>
                                <th>Autor</th>
                                <th>Votos Positivos</th>
                                <th>Votos Negativos</th>
                                </tr>
                                </thead>                                
                                <tbody>
                                    <?php                                
                                        require "classes/Administrar.php";                                    
                                        $imagenes = Administrar::VerEstadisticasGeneral();
                                        for($i = 0;$i<sizeof($imagenes);$i++){                                    
                                    ?>
                                    <tr>
                                        <td><?php echo $imagenes[$i]->name; ?></td> 
                                        <td><?php echo $imagenes[$i]->album; ?></td> 
                                        <td><?php echo $imagenes[$i]->nick; ?></td>
                                        <td><?php echo $imagenes[$i]->megusta; ?></td>
                                        <td><?php echo $imagenes[$i]->nomegusta; ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        </div>
                        </div>
                        </div>
                    </div>
                    </div>
                  
                </div>
            </div>            
        </div>
    </body>
</html>

