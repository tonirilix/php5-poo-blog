<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base
 *
 * @author Jesus Montes
 */
class Base {
    public function __construct(array $options){		
            foreach($options as $k=>$v){
                if(isset($this->$k)){
                        $this->$k = $v;
                }
            }
	}
}

?>
