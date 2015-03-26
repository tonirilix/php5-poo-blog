<?php

/* Configuracion de la base de datos. */

require_once '../includes/constants.php';

/* Database Config End */


//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(0);

require "../DataModel/ErrorDataModel.php";
require "../DataModel/UsuarioDataModel.php";
require "../classes/DB.php";
require "../classes/Base.php";
require "../classes/Administrar.php";

require "../classes/Rol.php";
require "../classes/CustomAuthorize.php";


/*HELPERS*/
require "../classHelpers/class.eyesecuresession.inc.php";


if(get_magic_quotes_gpc()){	
    // Si magic quotes estan habilitadsa, strip the extra slashes
    array_walk_recursive($_GET,create_function('&$v,$k','$v = stripslashes($v);'));
    array_walk_recursive($_POST,create_function('&$v,$k','$v = stripslashes($v);'));
}

try{
	
    // Connecting to the database
    DB::init($dbOptions);

    $response = array();

    // Handling the supported actions:

    switch($_GET['action']){

        case 'AprovarUsuario':
            $response = Administrar::AprovarUsuario($_POST['Usuario'],$_POST['Email']);           
        break;        
        
        case 'BanearUsuario':
            $response = Administrar::BanearUsuario($_POST['Usuario'], $_POST['Bloquear']);
        break;
        case 'CrearAlbum':
            $response = Administrar::CrearAlbum($_POST['Nombre'], $_POST['Descripcion'],@$_POST['IdAlbum']);           
        break; 
    
       case 'EliminarComentario':
            $response = Administrar::EliminarComentario($_POST['Comentario']);           
        break;
       
       case 'EliminarImagen':
            $response = Administrar::EliminarImagen($_POST['Imagen']);           
        break;
        
        case 'EliminarAlbum':
            $response = Administrar::EliminarAlbum($_POST['Album']);           
        break;
    
        case 'VerEstadisticasGeneral':
            $response = Administrar::VerEstadisticasGeneral();           
        break;
    
        case 'VerEstadisticasPorAbum':
            $response = Administrar::VerEstadisticasPorAlbum(); 
  
        break;
    
        default:
            throw new Exception('Accion no valida');
    }
    echo json_encode($response);
}
catch(Exception $e){
    $error = new ErrorDataModel();
    $error->Error = true;
    $error->Mensaje = $e->getMessage();
    die(json_encode(array('Error'=>$error))); 
}

?>