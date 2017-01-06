<?php

define('ROOT_DIR', __DIR__);

date_default_timezone_set("America/Bahia");

// Setting all necessary headers
//Headers::getAllHeaders();


function autoload($class_name) {

    $array_paths = array(
        'core'. DIRECTORY_SEPARATOR . 'interface', 
        'core'. DIRECTORY_SEPARATOR . 'config',
        'core'. DIRECTORY_SEPARATOR . 'libs'. DIRECTORY_SEPARATOR. 'personal',
        'core'. DIRECTORY_SEPARATOR . 'controller',
        'core'. DIRECTORY_SEPARATOR . 'bean',
        'core'. DIRECTORY_SEPARATOR . 'dao',
        'core'. DIRECTORY_SEPARATOR . 'utils'
    );

    foreach($array_paths as $path){
        $file = ROOT_DIR . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $class_name . ".php";        
        if(file_exists($file)){
            include $file;
        }
    }
}

spl_autoload_register('autoload');