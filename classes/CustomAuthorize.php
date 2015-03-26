<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomAuthorize
 *
 * @author ABCDE
 */
class CustomAuthorize {
    //put your code here
}

class OnlyAdministradorAuthorizeAttribute{
    public $resultado;
    public function __construct($Email,$Rol,$api = false) {        
        $Resultado = Rol::EstaUsuarioEnRol($Email,$Rol);        
        if($Resultado!= -1){            
            if($Resultado == 1){
                $this->resultado = $Resultado;;
            }
            else{
                if(!$api)
                {
                    header("Location: error.php?error=403");
                }
                else{
                    $this->resultado = $Resultado;
                }
            }
        }
        else{
            throw new Exception("Error inesperado. Intente mas tarde.");
        }

    }
}

?>
