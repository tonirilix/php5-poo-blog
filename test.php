<?php
require "DataModel/ErrorDataModel.php";

require "includes/constants.php";
require "classes/DB.php";
require "classes/Publicacion.php";
DB::init($dbOptions);
require "classes/Rol.php";

$d = Rol::EstaUsuarioEnRol("antonio@highbits.com", "Administrador");
if(!$d['Error']->Error){
    if($d['Estatus']){
        echo "simon";
    }
    else{
        echo "nel";
    }
}

?>
