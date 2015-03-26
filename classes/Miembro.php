<?php

class Miembro{
        
    public static function CrearUsuario($Email,$Username,$Password){
        if(!$Email || !$Username){
            throw new Exception('Llena todos los espacios requeridos, por favor.');
        }
        if(!filter_input(INPUT_POST,'Email',FILTER_VALIDATE_EMAIL)){
            throw new Exception('Tu email es incorrecto.');
        }
        
        //^.*\b(@conserva.org.mx)\b$
        
        ///Sirve para verificar si será necesario aprovar al usuario.
        $isApproved = 0;
        
        $seguridad = new Seguridad();                       
        
        if($seguridad->validarCorreo($Email)){
            try
            {
                $mbox=imap_open( "{mail.highbits.com:995/pop3/ssl/novalidate-cert}", $Email, $Password );
            }
            catch(Exception $e)
            {
                throw new Exception('Ocurri&oacute; un error al validar su usuario');
            }

            if ($mbox)
            {
                $isApproved = 1;
                imap_close($mbox);            
            }
            else{
                throw new Exception('El correo corporativo no existe o la contraseña es incorrecta. Verifique por favor.');
            }
        }                                
                                        
        $resultSeguridad = $seguridad->GenerarHash();
        
        
        $miembro = new MiembroUsuario(array(
                    'UserName'      => $Username,
                    'Password'      => $resultSeguridad['Hash'],
                    'PasswordSalt'  => $resultSeguridad['Salt'],
                    'Email'         => $Email,
                    'IsApproved'    => $isApproved
                ));
        
        $result = $miembro->Guardar();
        
        if($result['Estatus']!=0){
            throw new Exception('No se pudo registrar el usuario. El usuario ya existe');
        }
        
        $para = $Email;
        $titulo = 'Contraseña de Blog';
        if($isApproved)
            $mensaje = 'Hola tu contraseña para entrar al blog es la misma que la de tu correo';
        else
            $mensaje = 'Hola tu contraseña generada es '.$resultSeguridad['Password'].'. Puedes cambiarla un vez hayas iniciado sesion.';
        
        $cabeceras = 'From: noreplay@jesusmontes.mx' . "\r\n" .            
            'X-Mailer: PHP/' . phpversion();
        mail($para, $titulo, $mensaje, $cabeceras);
                
        
        $error = new ErrorDataModel();
        $error->Error = false;
        $error->Mensaje= "Cuenta creada correctamente. Revise su Email.";
        $error->Accion = "setTimeout('window.location =\"login.php\"',3000)";
        $result['Error'] = $error;        
        
        return $result;
    }
    
    public static function CambiarPassword($Password_old,$Password_new,$Password_new_confirm){
        if(!$Password_old || !$Password_new || !$Password_new_confirm){
            throw new Exception('Llena todos los espacios requeridos, por favor.');
        }        
        
        if($Password_new != $Password_new_confirm){
            throw new Exception("Las contrase&ntilde;as nuevas no coninciden");
        }
        
        //^.*\b(@conserva.org.mx)\b$
        
        ///Sirve para verificar si será necesario aprovar al usuario.
        $isApproved = 1;
        
        $seguridad = new Seguridad();                       
        $miembro = new MiembroUsuario(array());                                
        $GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
        $currentUserId = $GLOBAL_session->get('UsuarioActual');  
        
        $resultSeguridad = $seguridad->GenerarHash($Password_new);
                $miembro = new MiembroUsuario(array(
                    'UserName'      => $currentUserId->UserName,
                    'Password'      => $resultSeguridad['Hash'],
                    'PasswordSalt'  => $resultSeguridad['Salt'],
                    'Email'         => $currentUserId->Email,
                    'IsApproved'    => $isApproved
                ));
        
                $miembro->Actualizar();                

                $para = $Email;
                $titulo = 'El título';
                $mensaje = 'Hola tu contraseña es '.$resultSeguridad['Password'];
                $cabeceras = 'From: webmaster@example.com' . "\r\n" .            
                    'X-Mailer: PHP/' . phpversion();
                //mail($para, $titulo, $mensaje, $cabeceras);

                $error = new ErrorDataModel();        
                $error->Mensaje="XD";
                $result['Error'] = $error;

                $r = self::LogOut();
                return $r;
        /*$datosActuales = $miembro->VerExiste($currentUserId->Email);
        if($datosActuales['Password']!="" && $datosActuales['PasswordSalt']!=""){
            $datosGenerados = $seguridad->GenerarHash($Password_old, $datosActuales['PasswordSalt']);
            if($datosGenerados['Hash']==$datosActuales['Password']){
                $resultSeguridad = $seguridad->GenerarHash($Password_new);
                $miembro = new MiembroUsuario(array(
                    'UserName'      => $currentUserId->UserName,
                    'Password'      => $resultSeguridad['Hash'],
                    'PasswordSalt'  => $resultSeguridad['Salt'],
                    'Email'         => $currentUserId->Email,
                    'IsApproved'    => $isApproved
                ));
        
                $miembro->Actualizar();                

                $para = $Email;
                $titulo = 'El título';
                $mensaje = 'Hola tu contraseña es '.$resultSeguridad['Password'];
                $cabeceras = 'From: webmaster@example.com' . "\r\n" .            
                    'X-Mailer: PHP/' . phpversion();
                //mail($para, $titulo, $mensaje, $cabeceras);

                $error = new ErrorDataModel();        
                $error->Mensaje="XD";
                $result['Error'] = $error;

                self::LogOut();
            }
            else{
                throw new Exception("Verifique su contrase&ntilde;a.");
            }
        }                
        else{
                throw new Exception("Sucedio un error inesperado. Verifique su conexion.");
            }
         */         
    }
    
    public static function CambiarNombre($Nombre){        
        
        if($Nombre==""){
            throw new Exception("Escriba un nombre por favor.");
        }                        
                
        $GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
        $currentUserId = $GLOBAL_session->get('UsuarioActual');  
       
        if($Nombre == $currentUserId->UserName){
            throw new Exception("Escriba un nombre diferente del actual.");
        }
        
        $miembro = new MiembroUsuario(array(
            'UserName'      => $Nombre,            
            'Email'         => $currentUserId->Email            
        ));
       
        $miembro->ActualizarNombre();   
        
        $currentUserId->UserName = $Nombre;
        
        $GLOBAL_session->set('UsuarioActual',$currentUserId);  

        $error = new ErrorDataModel();        
        $error->Mensaje="Nombre Cambiado Correctamente";        
        $result['Error'] = $error;                
        return $result;
    }
    
    public static function CambiarImagen($Avatar,$_GLOBAL_session = null){
        if($_GLOBAL_session==null)
            $_GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
        $usuarioActual = $_GLOBAL_session->get('UsuarioActual');
        $currentUserId = $usuarioActual->UserId;        
         
        $CreadoPor = $currentUserId;
        $query = "CALL CONT_CambiarAvatar('".$currentUserId."','".DB::esc($Avatar)."');";
        $result = DB::query($query);
        if(DB::getMySQLiObject()->affected_rows != 1){
            throw new Exception("No se pudo guardar la imagen en la base de datos.");
        }                        
        $usuarioActual->Avatar = $Avatar;
        $_GLOBAL_session->set('UsuarioActual',$usuarioActual);
    }
    
    public static function LogIn($Email,$Password){
        if(!$Email || !$Password){
            throw new Exception('Llena todos los espacios requeridos, por favor.');
        }
        if(!filter_input(INPUT_POST,'Email',FILTER_VALIDATE_EMAIL)){
            throw new Exception('Tu email es incorrecto.');
        }
        
        $usuario = new UsuarioDataModel();
        $error = new ErrorDataModel();
        $seguridad = new Seguridad();
        $miembro = new MiembroUsuario(array());           
        
        $datosActuales = $miembro->VerExiste($Email);
        if($datosActuales['Password']!="" && $datosActuales['PasswordSalt']!=""){
            
            ///Sirve para verificar si será necesario aprovar al usuario.
            $isApproved = 0;          
            if($seguridad->validarCorreo($Email)){
                try
                {
                    $mbox=imap_open( "{mail.highbits.com:995/pop3/ssl/novalidate-cert}", $Email, $Password );
                }
                catch(Exception $e)
                {
                    throw new Exception('Ocurri&oacute; un error al validar su usuario');
                }

                if ($mbox)
                {
                    $isApproved = 1;
                    imap_close($mbox);            
                }
                else{
                    throw new Exception('La contraseña corporativa es incorrecta. Verifique por favor.');
                }
            }  
            
            $datosGenerados = $seguridad->GenerarHash($Password, $datosActuales['PasswordSalt']);
            if($datosGenerados['Hash']==$datosActuales['Password'] || $isApproved){
                if($datosActuales['isApproved']==1 && $datosActuales['isLockedOut'] == 0){
                    $usuario->Email = $Email;
                    $usuario->UserId = $datosActuales['UserId'];  
                    $usuario->UserName = $datosActuales['Username'];  
                    $usuario->Avatar = $datosActuales['Avatar'];  

                    $error->Mensaje = "Redireccionando. Espere...";
                    $error->Accion = "setTimeout(\"window.location = 'index.php'\",3000);";

                    $GLOBAL_session = new EyeSecureSession(SESSIONPASS);                
                    /*$GLOBAL_session->set('UserId', $usuario->UserId);
                    $GLOBAL_session->set('Username', $usuario->UserName);
                    $GLOBAL_session->set('Email', $usuario->Email);*/
                    $GLOBAL_session->set('UsuarioActual', $usuario);
                    $GLOBAL_session->set('Estatus', 'AUTORIZADO');
                }
                else{
                    throw new Exception("Contacte con el administrador, su cuenta puede esta bloqueada o inactiva.");
                }
            }
            else{
                throw new Exception("Los datos son incorrectos. Verifique por favor.");
            }
        }
        else{
            throw new Exception("El usuario no esta registrado");
        }
        
        return array(
                'Usuario' => $usuario,
                'Error' => $error
            );
        
    }
    
    public static function LogOut(){
        $error = new ErrorDataModel();
        $error->Mensaje = "Redirigiendo. Espere...";
        $error->Accion = "setTimeout(\"window.location = 'login.php'\",3000);";
        $GLOBAL_session = new EyeSecureSession(SESSIONPASS);               
        /*$GLOBAL_session->remove('UserId');     
        $GLOBAL_session->remove('Username');*/
        $GLOBAL_session->remove('Estatus');
        $GLOBAL_session->remove('UsuarioActual');
        return array(
            'Sesion' => 0,
            'Error' => $error
        );
    }          
    
    public static function EstaUsuarioEnRol(){
        
    }
	
}
?>
