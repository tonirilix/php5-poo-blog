<?php
/* CONFIGURACION. */
require_once '../includes/constants.php';

//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(0);

/*DATAMODELS*/
require "../DataModel/ErrorDataModel.php";
require "../DataModel/UsuarioDataModel.php";

require "../classes/DB.php";
require "../classes/Base.php";
require "../classes/Seguridad.php";
require "../classes/Miembro.php";
require "../classes/MiembroUsuario.php";

/*HELPERS*/
require "../classHelpers/class.eyesecuresession.inc.php";


if(get_magic_quotes_gpc()){	
    // Si magic quotes estan habilitadsa, strip the extra slashes
    array_walk_recursive($_GET,create_function('&$v,$k','$v = stripslashes($v);'));
    array_walk_recursive($_POST,create_function('&$v,$k','$v = stripslashes($v);'));
}

try{
    /*if($_GET['action']!='Iniciar' && $_GET['action']!='CrearUsuario'){
        $seguridad = new Seguridad();
        $seguridad->esSesionValida(true);    
    }*/
    
    // Connecting to the database
    DB::init($dbOptions);

    $response = array();

    // Handling the supported actions:

    switch($_GET['action']){

        case 'CrearUsuario':            
            $response = Miembro::CrearUsuario($_POST['Email'], $_POST['Username'],$_POST['Password']);            
        break;        
        case 'Iniciar':            
            $response = Miembro::LogIn($_POST['Email'], $_POST['Password']);
        break;            
        case 'Salir':            
            $response = Miembro::LogOut();
        break;        
        case 'CambiarPassword':            
            $response = Miembro::CambiarPassword($_POST['Password_old'], $_POST['Password_new'], $_POST['Password_new_confirm']);
        break;        
        case 'CambiarNombre':            
            $response = Miembro::CambiarNombre($_POST['Nombre']);
        break;        
        default:
            throw new Exception('Accion Invalida');
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