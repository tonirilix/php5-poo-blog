
var hbwkWall = {

    data: {
        section: 0,
        categoria: 0,        
        action_to_send_data: 0, 
        auth : false
    },

    init: function() {
        //inicializamos las funciones        
        var working = false;                
        var form_wall = $('#message_wall_form');
        $('#submit_button_wall').click(function(e) {
            e.preventDefault();            
            if (working) return false;
            working = true;

            var action = form_wall.attr('action');
            $.hbPOST(
                action,
                form_wall.serialize(),
                function(data) {
                    working = false;
                    $("#MainPublicationBox").val(function(i, val) {
                        //hbwkWall.addCommentLine(data);
                        hbwkWall.addComentarioLine(data.Comentario,hbwkWall.data.auth,true);
                    }).keyup();
                },
                function() { working = false; NOTIFICATIONS.displayNotification("Ocurrio un error mientras se realizaba la publicacion. Intente de nuevo mas tarde.",true); }
            );
            
        });

        $('textarea#MainPublicationBox').autoResize({
            // On resize:
            onResize: function() {

            },
            // Quite slow animation:
            animateDuration: 150,
            // More extra space:
            extraSpace: 0
        })
    },    

    render: function(template, params) {

        var arr = [];
        switch (template) {

            case 'commentLine':
                arr = [
                    '<dl id="', params.IdComm, '" style="display:none"><dt>',params.Usuario.UserName,': &nbsp;</dt>',params.Comentario.Comentario,'</dl>'
                    ];
                break;  
            case 'imagesLine':
                var Delete = '<a style="cursor:pointer;" onclick="$.eliminarImagen('+params.id+');">X</a>';
                var Edit = '<div><a rel="popupbox" href="#" onclick="jQuery.popupbox({ ajax: \'phpSections/pBox_GALE_ModificarDatosImagen.php?Imagen='+params.id+'&Titulo='+params.titulo+'\' }); return false;">Editar</a></div>';
                if(!params.Auth){ Delete = ""; Edit = ""; }
                arr =  [
                    '<li id="Imagen_', params.id, '" style="display:none; position:relative;">',
                        '<p style="position:absolute; bottom:5px; right:5px; font-weight:bold;"><a style="cursor:pointer;" onclick="$.calificarImagen(',params.id,',1)">+1</a>  <a style="cursor:pointer;" onclick="$.calificarImagen(',params.id,',-1)">-1</a>',Delete,'</p>',
                        '<a href="',params.path+params.name+"."+params.ext,'"',
                        'class="group" rel="gallGroup" title="',params.titulo,'" style="width:auto; height:105px; overflow:hidden;">',
                            '<img src="',params.path+params.name+"_thumbnail"+"."+params.ext,'"',
                            'alt="',params.description,'" style="margin:0 auto;" />',
                        '</a>',
                        '<span class="album-title">',
                            '<a href="#">Subido por:&nbsp;',params.nombre,'</a>',
                        '</span>',
                        '<div class="gfooter">',params.fecha_creacion,'</div>',                        
                        Edit,
                    '</li>',
                    '<script type="text/javascript" language="javascript">$(document).ready(function() { $("a[rel=gallGroup]").fancybox({titleShow:true});});</script>'                    
                ];
                break;
            case 'comentariosLine':
                var Delete2 = '<a style="cursor:pointer;" onclick="$.eliminarComentario('+params.idcomentario+');">X</a>';
                if(!params.Auth){ Delete2 = ""; }
                arr = [
                    '<dl id="Comentario_', params.idcomentario, '">',
                        '<dt>',params.username,': </dt>',
                        params.comentario,
                        '<dd>',Delete2,'</dd>',
                    '</dl>'
                ];
            break;
            case 'commentLine2':
                arr = [
                    '<li id="', params.IdComm, '" style=" width:100%; display:none;">',
                    '<span style="float:left; margin-left:3px; margin-top:3px;">',
                    '<a href="/Perfil/Ver?Usuario=', params.RegistradoPor, '" title="', params.Nombre, '">',
                    '<img src="/Content/Avatar/', params.Avatar, '" width="32px" height="32px" />',
                    '</a>',
                    '</span>',
                    '<span style="float:left; width:400px;">',
                    '<span style=" display:block; margin-left:3px; margin-top:0px; margin-bottom:3px; word-wrap: break-word;">',
                    params.Contenido, '</span></span></li>',
                    '<script>$("#', params.IdComm, ' a[href][title]").qtip({content: {text: false},style:"cream"}); </script>'];
                break;            

        }

        return arr.join('');

    },

    // El metodo addCommentLine agrega los cometarios existentes
    // de una publicacion
    addCommentLine: function(params) {

        params.IdComm = 't' + Math.round(Math.random() * 1000000);

        var markup = hbwkWall.render('commentLine', params);

        $('#listComentarios').prepend(markup);
        $('#' + params.IdComm).fadeIn("slow");

    },  
    
    addImageLine: function(params,Autorizacion) {

        //params.IdComm = 't' + Math.round(Math.random() * 1000000);
        params.Auth = Autorizacion;

        var markup = hbwkWall.render('imagesLine', params);

        $('#listImagenes').append(markup);
        $('#Imagen_' + params.id).fadeIn("slow");

    },  
    
    addComentarioLine: function(params,Autorizacion,single) {

        //params.IdComm = 't' + Math.round(Math.random() * 1000000);
        params.Auth = Autorizacion;
        
        var markup = hbwkWall.render('comentariosLine', params);

        if(!single)
            $('#listComentarios').append(markup);
        else
            $('#listComentarios').prepend(markup);
        $('#Comentario_' + params.idcomentario).fadeIn("slow");

    },  

    //Funcion like activa "me gusta" o "no me gusta" de acuerdo a cada clic que se de a una publicacion
    like: function(params) {
        var working_star = true;
        $.tzPOST("/Publicacion/MeGusta", { IdPubLike: params }, function(r) {
            var asdasdsadasd = r.Contenido;
            if (asdasdsadasd == "True") {
                $('#Like' + params).html("Ya no me gusta");
            }
            else {
                $('#Like' + params).html("Me gusta");
            }
        });
    }    
};