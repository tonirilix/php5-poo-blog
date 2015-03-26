<?php

/* Configuracion de la base de datos. */

include '../includes/constants.php';

/* Database Config End */


//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(0);

require "../DataModel/ErrorDataModel.php";
require "../DataModel/UsuarioDataModel.php";
require "../classes/DB.php";
require "../classes/Base.php";
require "../classes/Publicacion.php";
require "../classes/Upload.php";

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

        case 'AgregarImagen':
            $response = Publicacion::AgregarImagen($_POST['Titulo'],$_POST['Descripcion'], @$_POST['IdAlbum'],@$_POST['RutaImagen'],@$_POST['NombreImagen'],@$_POST['ExtensionImagen'],null,@$_POST['IdImagen']);
        break;        
        
        case 'AgregarComentarioAAlbum':
            $response = Publicacion::AgregarComentarioAAlbum($_POST['Album'], $_POST['Comentario']);           
        break; 
    
        case 'ObtenerComentariosAlbum':
            $response = Publicacion::ObtenerComentariosAlbum($_POST['Album']);
        break;
    
       case 'CalificarImagen':
            $response = Publicacion::CalificarImagen($_POST['Imagen'], $_POST['Voto']);           
        break;
       
       case 'ObtnerAlbumes':
            $response = Publicacion::ObtenerAlbumes();           
        break;
		
        case 'ObtnerAlbum':
            $response = Publicacion::ObtenerAlbum($_POST['IdAlbum']);           
        break;
        
        case 'ObtenerImagenes':
            $response = Publicacion::ObtenerImagenes($_POST['IdAlbum']);           
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