<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Rol
 *
 * @author ABCDE
 */
class Rol {
    //put your code here
    public static function EstaUsuarioEnRol($Email,$Rol) {        
        $Retorno = 0;
        $query = "CALL MIEM_EstaUsuarioEnRol('".DB::esc($Email)."','".DB::esc($Rol)."',@Estatus);";
        $result = DB::query($query);       
        
        $result = DB::query("SELECT @Estatus as Estatus");
        
        if($result){
            $data = $result->fetch_object();                    
            $Retorno = $data->Estatus;
        }
        else{
            $Retorno = -1;
        }

        return $Retorno;
    }
}

?>
