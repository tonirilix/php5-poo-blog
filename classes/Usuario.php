<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usuario
 *
 * @author ABCDE
 */
class Usuario {
    //put your code here
    public function ObtenerInformacionUsuario() {
        $GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
        $currentUserId = $GLOBAL_session->get('UsuarioActual');        
        $result = DB::query("CALL MIEM_ObtenerInformacionUsuario('".DB::esc($currentUserId->UserId)."');");
        $usuario = new UsuarioDataModel();
        while($item = $result->fetch_object()){
            $usuario->Avatar = $item->Avatar;
            $usuario->Email = $item->Email;
            $usuario->UserId = $IdUsuario;
            $usuario->UserName = $item->Username;
        }
        
        return $usuario;
    }
}

?>
