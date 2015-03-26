<?php

const DEVMODE = false;

if(!DEVMODE){
    $dbOptions = array(
	'db_host' => 'localhost',
	'db_user' => 'USUARIO_BASE',
	'db_pass' => 'PASS_BASE',
	'db_name' => 'NOMBRE_BASE'
    );
    
}
else{    
    $dbOptions = array(
            'db_host' => 'localhost',
            'db_user' => 'root',
            'db_pass' => '',
            'db_name' => 'BASE_LOCAL'
    );
}

const SESSIONPASS = "O0QL5e+siOhDj5j7Ulhr9Q6qQE5kZWU1MjdlYmZl";

?>
