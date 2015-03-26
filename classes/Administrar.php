<?php

class Administrar{
    public static function ObtenerTodosLosUsuarios(){
        
        $result = DB::query("CALL MIEM_ObtenerTodosLosUsuarios()");
        while($item = $result->fetch_object()){                      
            $usuarios[] = $item;            
        }   
        return $usuarios;
    }
     public static function AprovarUsuario($Usuario,$Email){
         
         $GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
         $currentUserId = $GLOBAL_session->get('UsuarioActual');        
         $AccesoRestringido = new OnlyAdministradorAuthorizeAttribute($currentUserId->Email, "Administrador", true);                  
         if($AccesoRestringido->resultado == 0 || $AccesoRestringido->resultado != 1){
             throw new Exception('Acceso Denegado. No cuenta con permisos suficientes.');
         }
         
         $error = new ErrorDataModel();    
         $datos = "'".DB::esc($Usuario)."'";
         $result = DB::query("CALL MIEM_AprovarUsuario(".$datos.");");        

         
        if(DB::getMySQLiObject()->affected_rows != 1){
            throw new Exception('Fallo la aprovacion del usuario.');
        }                
        
        $para = $Email;
        $titulo = 'Aprobacion de cuenta en Blog';
        $mensaje = 'Hola. Tu cuenta ha sido aprobada, ya puede iniciar sesion.';
        
        $cabeceras = 'From: contacto@conserva.org.mx' . "\r\n" .            
            'X-Mailer: PHP/' . phpversion();
        @mail($para, $titulo, $mensaje, $cabeceras);
        
        
        
        $error->Mensaje = "La Aprovacion se realizo correctamente.";
        $error->Accion = "window.location = 'admusuarios.php'";
        
        return array(
            'Error'=> $error
        );       
       
     }
     
     public static function BanearUsuario($Usuario,$Bloquear){
         $GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
         $currentUserId = $GLOBAL_session->get('UsuarioActual');        
         $AccesoRestringido = new OnlyAdministradorAuthorizeAttribute($currentUserId->Email, "Administrador", true);         
         if($AccesoRestringido->resultado == 0){
             throw new Exception('Acceso Denegado. No cuenta con permisos suficientes.');
         }
         
         $error = new ErrorDataModel();    
         $datos = "'".DB::esc($Usuario)."',$Bloquear";
         DB::query("CALL MIEM_BloquearUsuario(".$datos.");");        

         
         if(DB::getMySQLiObject()->affected_rows != 1){
            throw new Exception('Fallo el bloqueo del usuario.');
        }
        
        $error->Mensaje = "El bloqueo se realizo correctamente.";
        $error->Accion = "window.location = 'admusuarios.php'";
        
        return array(
            'Error'=> $error
        );       
       
     }
     
     public static function CrearAlbum($Nombre, $Descripcion, $IdAlbum = 0){
         
         $GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
         $currentUserId = $GLOBAL_session->get('UsuarioActual');        
         $AccesoRestringido = new OnlyAdministradorAuthorizeAttribute($currentUserId->Email, "Administrador", true);                  
         if($AccesoRestringido->resultado == 0 || $AccesoRestringido->resultado != 1){
             throw new Exception('Acceso Denegado. No cuenta con permisos suficientes.');
         }                  
                  
         $currentUserId = $GLOBAL_session->get('UsuarioActual')->UserId;        
         
         $CreadoPor = $currentUserId;
         
         if(!$Nombre || !$Descripcion){
            throw new Exception('Debe capturar los datos requeridos.');
         }                  
         
         $result = DB::query("CALL GALE_CrearAlbum('".DB::esc($Nombre)."','".DB::esc($Descripcion)."','".$CreadoPor."',".$IdAlbum.");");
        
         if(DB::getMySQLiObject()->affected_rows != 1){
            throw new Exception('Fallo la creacion del album.');
         }
         
         $album = array();
        while($item = $result->fetch_object()){
            $album[] = $item;
        }          

        if($album[0]->Estatus!=0){
            throw new Exception("No se pudo crear el album.");
        }
        
        $error = new ErrorDataModel();        
        $error->Mensaje =  $IdAlbum!=0?"Album modificado correctamente...":"El album fue creado exitosamente...";
        $error->Accion = "setTimeout(\"window.location = 'admaddalbum.php'\",3000);";
        //$error->Accion = "$('#tableAlbumes').prepend();";

        return array(
            'Album' =>  $album[0]->IdAlbum,
            'Error'=> $error
        );                       
        
     }
     
        
    public static function EliminarComentario($Comentario){
        $GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
        $currentUserId = $GLOBAL_session->get('UsuarioActual');        
        $AccesoRestringido = new OnlyAdministradorAuthorizeAttribute($currentUserId->Email, "Administrador", true);                  
        if($AccesoRestringido->resultado == 0 || $AccesoRestringido->resultado != 1){
            throw new Exception('Acceso Denegado. No cuenta con permisos suficientes.');
        }
        
        $result = DB::query("CALL GALE_EliminarComentario(".DB::esc($Comentario).");");        

         
        /*if(DB::getMySQLiObject()->affected_rows != 1){
            throw new Exception('Fallo la eliminacion del comentario.');
        }*/
        if(!$result){
            throw new Exception('Fallo la eliminacion del comentario.');
        }
        
        $error = new ErrorDataModel();
        $error->Mensaje='La eliminacion del comentario se realizo correctamente.';
        $error->Accion="$('#Comentario_".$Comentario."').fadeOut(300);";
        return array(
            'Error'=> $error
        );  
    }
    
    public static function EliminarImagen($Imagen){
         
        $GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
        $currentUserId = $GLOBAL_session->get('UsuarioActual');        
        $AccesoRestringido = new OnlyAdministradorAuthorizeAttribute($currentUserId->Email, "Administrador", true);                  
        if($AccesoRestringido->resultado == 0 || $AccesoRestringido->resultado != 1){
            throw new Exception('Acceso Denegado. No cuenta con permisos suficientes.');
        }
        
        DB::query("CALL GALE_EliminarImagen(".DB::esc($Imagen).");");        

         
         if(DB::getMySQLiObject()->affected_rows != 1){
            throw new Exception('Fallo la eliminacion de la imagen.');
        }
        
        $error = new ErrorDataModel();
        $error->Mensaje='La eliminacion de la imagen se realizo correctamente.';
        $error->Accion="$('#Imagen_".$Imagen."').fadeOut(300);";
        return array(
            'Error'=> $error
        );  
    }
    
    public static function EliminarAlbum($Album){
         
        $GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
        $currentUserId = $GLOBAL_session->get('UsuarioActual');        
        $AccesoRestringido = new OnlyAdministradorAuthorizeAttribute($currentUserId->Email, "Administrador", true);                  
        if($AccesoRestringido->resultado == 0 || $AccesoRestringido->resultado != 1){
            throw new Exception('Acceso Denegado. No cuenta con permisos suficientes.');
        }

        DB::query("CALL GALE_EliminarAlbum(".DB::esc($Album).");");        

         
         if(DB::getMySQLiObject()->affected_rows != 1){
            throw new Exception('Fallo la eliminacion del album.');
        }
        
        $error = new ErrorDataModel();
        $error->Mensaje="La eliminacion del album se realizo correctamente.";
        $error->Accion = "$('#AlbumContainer_".$Album."').fadeOut(300);";
        return array(
            'Error'=> $error
        );  
    }
    
    public static function VerEstadisticasGeneral(){
              
          $Imagenes = array();
          $result = DB::query("CALL GALE_VerEstadisticaGeneral();"); 
          
           while($item = $result->fetch_object()){
                      
            $Imagenes[] = $item;
            
        }              
        
        return  $Imagenes;
           
     }
     
      public static function VerEstadisticasPorAlbum(){
              
          $Imagenes = array();
          $result = DB::query("CALL GALE_VerEstadisticaPorAlbum();"); 
          
           while($item = $result->fetch_object()){
                      
            $Imagenes[] = $item;
            
        }            
        
        return  $Imagenes;           
     }
    
}
?>
