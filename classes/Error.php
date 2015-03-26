<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Error
 *
 * @author ABCDE
 */
class Error {
    private $errores = array(
        '404' => "Pagina no encontrada",
        '403' => "Acceso Restringido"        
    );    
    public function ObtenerError($numeroError){
        $infoError = @$this->errores[$numeroError]?@$this->errores[$numeroError]: $this->errores["404"];
        echo $infoError;
    }
}

?>
