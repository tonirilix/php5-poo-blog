<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Seguridad
 *
 * @author ABCDE
 */
class Seguridad {
    //put your code here
    protected $regex_email_corporativo = "/^.*\b(@conserva.org.mx)\b/";
    public function GenerarHash($password = "",$salt_ext = ""){
        
        $salt = sha1(uniqid(mt_rand(), true));
        $salt = substr($salt, 0, 10);
        
        $pass = $password != "" ? $password : $this->createRandomPassword();
        $salt = $salt_ext != "" ? $salt_ext : $salt;
                
        $hash = base64_encode( sha1($pass . $salt, true) . $salt );                
        
        return array(
            'Hash'      => $hash,
            'Salt'      => $salt,
            'Password'  => $pass
        );         
        
    }
    
    private function createRandomPassword() {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        
        srand((double)microtime()*1000000);
        
        $i = 0;

        $pass = '' ;

        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;

    }        

    public function validarCorreo($text)
    {        
	if (preg_match($this->regex_email_corporativo, $text)) {
            return TRUE;
	} 
	else {
            return FALSE;
	}
    } 
    
    public function esSesionValida($GLOBAL_session,$api = false) {        
        $estatusSesion = $GLOBAL_session->get('Estatus');        
        $usuarioActual = $GLOBAL_session->get('UsuarioActual');
        if($estatusSesion!='AUTORIZADO' || $usuarioActual->UserId == ""){            
            if(!$api){                  
                header("Location: login.php");
            }
            else{
                throw new Exception("Por favor, Inicie sesion");                
            }
        }
        else{
            return true;
        }
    }            

}

?>
