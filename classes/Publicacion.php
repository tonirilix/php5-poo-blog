<?php

class Publicacion{
    var $dir_dest = "../images/";
    
    public static function AgregarImagen($Titulo, $Descripcion, $IdAlbum=0, $RutaImagen="", $NombreImagen="",$ExtensionImagen="", $_GLOBAL_session = null,$IdImagen = 0){         
         if($_GLOBAL_session == null)
            $_GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
         
         $currentUserId = $_GLOBAL_session->get('UsuarioActual')->UserId;        
         
         $CreadoPor = $currentUserId;
         $Alto = 0;
         $Ancho = 0;
         
         if(!$Descripcion || !$Titulo){
            throw new Exception('Debe capturar los datos requeridos.');
            }
        
        //especificar el nombre del campo tipo file del formulario
        
        if($IdAlbum=="") $IdAlbum = 0;
            
        $query = "CALL GALE_AgregarImagen('".$RutaImagen."','".$NombreImagen."','".$ExtensionImagen."',".$Ancho.",".$Alto.",'".DB::esc($Titulo)."','".DB::esc($Descripcion)."','".$CreadoPor."',".$IdAlbum.",".$IdImagen.");";
        $result = DB::query($query);
        
        if(DB::getMySQLiObject()->affected_rows != 1){
            throw new Exception('No se pudo agregar la imagen.');
        }
        
        while($item = $result->fetch_object()){
            $IdImagen = $item->IdImagen;
        }        
        
        
        $error = @new ErrorDataModel();
        $error->Error = false;
        $error->Mensaje = $IdImagen == 0?"La imagen se agrego exitosamente.":"Imagen actualizada correctamente";
        
        return array(
            'Error'=>$error,            
            'Titulo'=>$Titulo,
            'Mensage' => 'La imagen se agrego exitosamente.',
            'Imagen' =>  $IdImagen
        );
        
        
     }
     
     public static function AgregarComentarioAAlbum($Album, $Comentario){
         $IdComentario = "";
         $GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
         $usuarioActual = $GLOBAL_session->get('UsuarioActual');
         $currentUserId = $usuarioActual->UserId;        
         
         $CreadoPor = $currentUserId;
         
         if(!$Comentario){
            throw new Exception('Debe escribir un comentario.');
            }
            
        //$result = DB::query("CALL GALE_AgregarComentarioAAlbum(".DB::esc($Album).",'".$CreadoPor."','".DB::esc($Comentario)."');");
        
            $query = "CALL GALE_AgregarComentarioAAlbum(".DB::esc($Album).",'".$CreadoPor."','".DB::esc($Comentario)."');";
            $query .= "CALL MIEM_ObtenerInformacionUsuario('".DB::esc($CreadoPor)."');";            
                
            DB::getMySQLiObject()->multi_query($query);
            
            if ($result = DB::getMySQLiObject()->store_result()) {
            while ($item = $result->fetch_object()) {
                $IdComentario = $item->IdComentario;
            }
                $result->free();
            }
            
            DB::getMySQLiObject()->more_results();
            
            if ($result = DB::getMySQLiObject()->store_result()) {
                $usuario = new UsuarioDataModel();
            while ($item = $result->fetch_object()) {
                $usuario->Avatar = $item->Avatar;
                $usuario->Email = $item->Email;
                $usuario->UserId = $IdUsuario;
                $usuario->UserName = $item->Username;
            }
                $result->free();
            }
            else{
                $usuario->Avatar = $usuarioActual->Avatar;
                $usuario->Email = $usuarioActual->Email;
                $usuario->UserId = $CreadoPor;
                $usuario->UserName = $usuarioActual->UserName;
            }
                        
        
        $error = new ErrorDataModel();                                                
        
        return array(       
            'Usuario' => $usuario,
            'Comentario'=>array(
                'idcomentario'=>$IdComentario,
                'comentario'=>$Comentario,
                'fecha_creacion' => '',
                'username'=>$usuarioActual->UserName,
                'avatar'=> $usuarioActual->Avatar
            ),            
            'Error' => $error
        ); 
        
     }
     
     public static function ObtenerComentariosAlbum($Album){
         $result = DB::query("CALL GALE_ObtenerMensajesAlbum(".$Album.")");
         $mensajes = array();
         while($item = $result->fetch_object()){
             $mensajes[] = $item;
         }
         
         return array(
             'Error' => new ErrorDataModel(),
             'Comentarios' =>$mensajes
         );
     }
     
     public static function CalificarImagen($Imagen, $Voto){
         $GLOBAL_session = new EyeSecureSession(SESSIONPASS);                                
         $currentUserId = $GLOBAL_session->get('UsuarioActual')->UserId;        
         
         $CreadoPor = $currentUserId;
         $query = "CALL GALE_CalificarImagen(".DB::esc($Imagen).",'".$CreadoPor."',".DB::esc($Voto).");";
         $result = DB::query($query);        
         
         /*if(DB::getMySQLiObject()->affected_rows != 1){
            throw new Exception('Fallo calificar la imagen.');
        }*/
         if(!$result){
             throw new Exception('Fallo calificar la imagen.');
         }
        $error = new ErrorDataModel();
        $error->Mensaje = "la calificacion se realizo correctamente.";
        return array(
            'Error'=> $error
        );       
       
     }
     
         
           
    public static function ObtenerAlbumes(){
              
          $Albumes = array();
          $result = DB::query("CALL GALE_ObtenerAlbumes();"); 
          
           while($item = $result->fetch_object()){
                      
            $Albumes[] = $item;
            
        }              
        
        return  $Albumes;
           
     }
	 
	 public static function ObtenerAlbum($IdAlbum){
        $Album = array(
            'Id' => '',
            'Nombre' =>'',
            'Descripcion' => '',
            'Fecha' => '',
            'Autor' => ''
        );
          $result = DB::query("CALL GALE_ObtenerAlbum(".DB::esc($IdAlbum).");"); 
          
           while($item = $result->fetch_object()){
                      
            $Album['Id'] = $item->id;
            $Album['Nombre'] = $item->nombre;
            $Album['Descripcion'] = $item->descripcion;
            $Album['Fecha'] = $item->fecha_creacion;
            $Album['Autor'] = $item->author;
            
        }              
        
        return  $Album;
           
     }
     
            public static function ObtenerImagenes($IdAlbum){          

          $Imagenes = array();
         
          $result = DB::query("CALL GALE_ObtenerImagenes(".DB::esc($IdAlbum).");");
          
           while($item = $result->fetch_object()){
                      
            $Imagenes[] = $item;
            
        }              
        
        $error = new ErrorDataModel();
        $error->Accion = "";
        
        return  array(
            'Imagenes' => $Imagenes,
            'Error' => $error
            );
     }
     
     private static function SubirImagen($NameField)
     {
       
       $handle = new Upload($_FILES[$NameField]);

    // then we check if the file has been uploaded properly
     if ($handle->uploaded) {

        // yes, the file is on the server
        // below are some example settings which can be used if the uploaded file is an image.
        $handle->image_resize            = true;
        $handle->image_ratio_y           = true;
        $handle->image_x                 = 300;

        // now, we start the upload 'process'. That is, to copy the uploaded file
        // from its temporary location to the wanted location
        // It could be something like $handle->Process('/home/www/my_uploads/');
        $handle->Process($this->dir_dest);
        
         // we check if everything went OK
        if ($handle->processed) {
            //path y name $dir_pics.'/' . $handle->file_dst_name
           crearThumbnail($handle->file_dst_name);
           return $handle->file_dst_name;
        }
        else {
            return "";
        }
         // we delete the temporary files
        $handle-> Clean();
        
        }
       else {
                return "";
            }
     }
     
     private static function crearThumbnail($NombreImg)
     {
      ini_set("max_execution_time",0);
           // we don't upload, we just send a local filename (image)
      $handle; //= new Upload($_GET['file'] : $NombreImg);

    // then we check if the file has been "uploaded" properly
    // in our case, it means if the file is present on the local file system
    if ($handle->uploaded) {

       if (!file_exists($this->dir_dest)) mkdir($dir_dest);
       
       // -----------
        $handle->image_resize          = true;
        $handle->image_ratio_y         = true;
        $handle->image_x               = 50;
        
        $handle->Process($this->dir_dest);

            if ($handle->processed) {
               //directorio $dir_pics.'/' . $handle->file_dst_name
               return true;
            } else {
               return false;
            }
       
       } else {
        // if we are here, the local file failed for some reasons
        return false;
        }    
     }
        
}
?>
