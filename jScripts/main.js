$(document).ready(function() {
    /*-----------------------------------------------------------------------------------*/
    /* 1. TOOLTIP
    /*-----------------------------------------------------------------------------------*/
    if($().tipsy) {        
        $('[rel=tipsy], acronym[title], .tooltip').tipsy({
            fade: true,
            gravity: 's'
        });
    } 
    
    
    if($().popupbox) {                
        jQuery('a[rel*=popupbox]').popupbox() 
    } 
    
    /*-----------------------------------------------------------------------------------*/
    /* 2. Lista de Usuarios
    /*-----------------------------------------------------------------------------------*/
    $.aprovarUsuario = function(UserId,email){        
        $.hbPOST("api/apiAdministrar.php?action=AprovarUsuario", {Usuario:UserId,Email:email}, 
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    }
                    else{
                        NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                        eval(data.Error.Accion);                            
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }
    
    
    /*-----------------------------------------------------------------------------------*/
    /* 3. Login
    /*-----------------------------------------------------------------------------------*/
    $.IniciarSesion = function(){ 
        $('#infologin').removeClass().addClass('box').addClass('warning').text('Iniciando sesion. Por favor espere...').fadeIn(1000);
        $('#btnLogin').attr('disabled','disabled').attr('value','Iniciando...');        
        $.hbPOST("api/apiMiembro.php?action=Iniciar", $('#IniciarSesion').serialize(), 
            function(data){
                if(data.Error.Error == true)
                {
                    //NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    $('#infologin').text(data.Error.Mensaje).removeClass('warning').addClass('alert').fadeIn(5000,function(){
                        $(this).fadeOut(5000);
                    });
                    $('#btnLogin').removeAttr("disabled").attr('value','Iniciar');        
                }
                else
                {
                    //NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                    $('#infologin').removeClass('warning').addClass('success').text(data.Error.Mensaje).fadeIn(1000);
                    eval(data.Error.Accion);                                                                    
                }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }
    
    /*-----------------------------------------------------------------------------------*/
    /* 4. Logout
    /*-----------------------------------------------------------------------------------*/
    $.CerrarSesion = function(){        
        $.hbPOST("api/apiMiembro.php?action=Salir", {}, 
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);
                    }
                    else{
                        NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                        eval(data.Error.Accion);
                    }
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }
    
    /*-----------------------------------------------------------------------------------*/
    /* 5. Registro
    /*-----------------------------------------------------------------------------------*/
    $.Registrar = function(){        
        $.hbPOST("api/apiMiembro.php?action=CrearUsuario", $('#Form').serialize(), 
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    }
                    else{
                        NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                        eval(data.Error.Accion);                            
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }
    
    /*-----------------------------------------------------------------------------------*/
    /* 7. Crear Album
    /*-----------------------------------------------------------------------------------*/
    $.CrearAlbum = function(accion){           
        
        $('#btnCrearAlbum').attr('disabled','disabled').attr('value','Guardando album...');        
        $.hbPOST("api/apiAdministrar.php?action=CrearAlbum", $('#Form').serialize(), 
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    $('#btnCrearAlbum').removeAttr("disabled").attr('value','Guardar Album');        
                    }
                    else{
                        NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                        eval(data.Error.Accion);                            
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }
    
    /*-----------------------------------------------------------------------------------*/
    /* 8. Lista de Usuarios
    /*-----------------------------------------------------------------------------------*/
    $.eliminarAlbum = function(IdAlbum){                        
        if(!confirmarAccion())
            return;
        IdAlbum = IdAlbum.replace("Album_","");
        $.hbPOST("api/apiAdministrar.php?action=EliminarAlbum", {Album:IdAlbum}, 
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    }
                    else{
                        NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                        eval(data.Error.Accion);                            
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }        
    
    /*-----------------------------------------------------------------------------------*/
    /* 9. Confirmar Accion
    /*-----------------------------------------------------------------------------------*/
    function confirmarAccion(){
        var preg=confirm ("Estas seguro que quieres eliminar esas entradas?");
        return preg;
    }    
    
    /*-----------------------------------------------------------------------------------*/
    /* 10. Cambiar password
    /*-----------------------------------------------------------------------------------*/
    $.CambiarPassword = function(){        
        $.hbPOST("api/apiMiembro.php?action=CambiarPassword", $('#FormCContrasenia').serialize(), 
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    }
                    else{
                        NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                        eval(data.Error.Accion);                            
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }
    
    /*-----------------------------------------------------------------------------------*/
    /* 11. Obtener imagenes de album;
    /*-----------------------------------------------------------------------------------*/
    $.ObtenerImagenesDeAlbum = function(Album,Autorizacion){              
        $.hbPOST("api/apiPublicacion.php?action=ObtenerImagenes", {IdAlbum:Album}, 
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    }
                    else{
                        if(data.Error.Mensaje!="")
                            NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                        eval(data.Error.Accion);                            
                        
                        for(var i=0;i<data.Imagenes.length;i++){
                            hbwkWall.addImageLine(data.Imagenes[i],Autorizacion);
                        }
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }
    
    /*-----------------------------------------------------------------------------------*/
    /* 11. Obtener imagenes de album;
    /*-----------------------------------------------------------------------------------*/
    $.ObtenerComentariosDeAlbum = function(IdAlbum,Autorizacion){              
        $.hbPOST("api/apiPublicacion.php?action=ObtenerComentariosAlbum", {Album:IdAlbum}, 
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    }
                    else{
                        if(data.Error.Mensaje!="")
                            NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                        eval(data.Error.Accion);                            
                        
                        for(var i=0;i<data.Comentarios.length;i++){
                            hbwkWall.addComentarioLine(data.Comentarios[i],Autorizacion);
                        }
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }
    
    /*-----------------------------------------------------------------------------------*/
    /* 12. Bloquear/Desbloquear Usuario;
    /*-----------------------------------------------------------------------------------*/
   
    $.banearUsuario = function(IdUser,bloquear){                      
        $.hbPOST("api/apiAdministrar.php?action=BanearUsuario", {Usuario:IdUser,Bloquear:bloquear},         
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    }
                    else{
                        if(data.Error.Mensaje!="")
                            NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                            eval(data.Error.Accion);                            
                                                
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }
    
    
    /*-----------------------------------------------------------------------------------*/
    /* 13. Calificar Imagen
    /*-----------------------------------------------------------------------------------*/    
    $.calificarImagen = function(IdImagen,ValorVoto){                      
        $.hbPOST("api/apiPublicacion.php?action=CalificarImagen", {Imagen:IdImagen,Voto:ValorVoto},         
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    }
                    else{
                        if(data.Error.Mensaje!="")
                            NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                            eval(data.Error.Accion);                            
                                                
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }    
    
    /*-----------------------------------------------------------------------------------*/
    /* 14. Eliminar Imagen;
    /*-----------------------------------------------------------------------------------*/
    $.eliminarImagen = function(IdImagen){                      
        if(!confirmarAccion())
            return;
        $.hbPOST("api/apiAdministrar.php?action=EliminarImagen", {Imagen:IdImagen},         
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    }
                    else{
                        if(data.Error.Mensaje!="")
                            NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                            eval(data.Error.Accion);                            
                                                
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    } 
    /*-----------------------------------------------------------------------------------*/
    /* 15. Eliminar Comentario;
    /*-----------------------------------------------------------------------------------*/
     $.eliminarComentario = function(Idcomentario){                      
        if(!confirmarAccion())
            return;
        $.hbPOST("api/apiAdministrar.php?action=EliminarComentario", {Comentario:Idcomentario},         
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    }
                    else{
                        if(data.Error.Mensaje!="")
                            NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                            eval(data.Error.Accion);                            
                                                
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    } 
});

    /*-----------------------------------------------------------------------------------*/
    /* 16. Cambiar Nombre
    /*-----------------------------------------------------------------------------------*/
    $.CambiarNombre = function(){        
        $.hbPOST("api/apiMiembro.php?action=CambiarNombre", $('#FormCNombre').serialize(), 
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    }
                    else{
                        NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                        eval(data.Error.Accion);                            
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }
    
    /*-----------------------------------------------------------------------------------*/
    /* 17. Editar nombre y descripcion imagen;
    /*-----------------------------------------------------------------------------------*/
    $.EditarImagen = function(){              
        $.hbPOST("api/apiPublicacion.php?action=AgregarImagen",$('#FormEditarImagen').serialize(), 
            function(data){
                if(data.Error.Error == true){
                    NOTIFICATIONS.displayNotification(data.Error.Mensaje,true);                                
                    }
                    else{
                        if(data.Error.Mensaje!="")
                            NOTIFICATIONS.displayNotification(data.Error.Mensaje,false);
                        $('#Imagen_'+data.Imagen+' a.group').attr('title',data.Titulo);
                        $('.close').click();
                    }                            
            }, 
            function(){
                NOTIFICATIONS.displayNotification("Ocurrio un error. Intente mas tarde",false);
            }            
        );
    }