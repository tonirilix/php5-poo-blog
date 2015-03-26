<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MiembroUsuario
 *
 * @author ABCDE
 */
class MiembroUsuario extends Base{
    protected $UserName ="",$Password ="", $PasswordSalt = "",$Email = "", $IsApproved = 1,
              $UserId = "", $Estatus = 0;
    
    function Guardar() {        
        $query = "CALL MIEM_CrearSeguridadUsuario(
            '".DB::esc($this->UserName)."',
            '".DB::esc($this->Password)."',
            '".DB::esc($this->PasswordSalt)."',
            '".DB::esc($this->Email)."',
            ".$this->IsApproved.");";
        $result = DB::query($query);            
                          
        while($item = $result->fetch_object()){
            $this->UserId = $item->UserId;
            $this->Estatus = $item->Estatus;            
        }              
        
        return array(
            'UserId' => $this->UserId,
            'Estatus' => $this->Estatus
        );
    }
    
    function Actualizar() {        
        $query = "CALL MIEM_CambiarPassword(
            '".DB::esc($this->Email)."',            
            '".DB::esc($this->Password)."',
            '".DB::esc($this->PasswordSalt)."');";
        $result = DB::query($query);            
                          
        if(DB::getMySQLiObject()->affected_rows != 1){
            throw new Exception('Fallo la aprovacion del usuario.');
        }
        
        return array(            
            'Estatus' => 1
        );
    }
    
    function ActualizarNombre() {        
        $query = "CALL MIEM_CambiarNombre(            
            '".DB::esc($this->Email)."',            
            '".DB::esc($this->UserName)."');";
        $result = DB::query($query);            
                          
        if(DB::getMySQLiObject()->affected_rows != 1){
            throw new Exception('No se pudo cambiar el nombre del usuario.');
        }
        
        return array(            
            'Estatus' => 1
        );
    }
    
    function VerExiste($Email){
                
        $query = "CALL MIEM_Login('".DB::esc($Email)."');";                        
        $result = DB::query($query);                                
        
        $datosDeLogin = array(
            'UserId' => "",
            'Password' => "",
            'PasswordSalt' => "",
            'Username' => "",
            'Avatar' => ""
        );
        
        while($item = $result->fetch_object()){
            $datosDeLogin['UserId'] = $item->idusuario;
            $datosDeLogin['Password'] = $item->password;
            $datosDeLogin['PasswordSalt'] = $item->passwordSalt;
            $datosDeLogin['Username'] = $item->username;
            $datosDeLogin['Avatar'] = $item->avatar;
            $datosDeLogin['isApproved'] = $item->isApproved;
            $datosDeLogin['isLockedOut'] = $item->isLockedOut;
        }  
        
        //$result->free();
        
        return $datosDeLogin;
    }
}

?>
