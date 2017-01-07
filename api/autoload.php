<?php

/**
 * This file contain the autoload function and all standard behaviour of API
 */

define('ROOT_DIR', __DIR__);

function autoload($class_name) {

    $array_paths = array(
        'core'. DIRECTORY_SEPARATOR . 'interface', 
        'core'. DIRECTORY_SEPARATOR . 'config',
        'core'. DIRECTORY_SEPARATOR . 'controller',
        'core'. DIRECTORY_SEPARATOR . 'bean',
        'core'. DIRECTORY_SEPARATOR . 'dao',
        'core'. DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . "database",
        'core'. DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . "security",
        'core'. DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . "response",
        'core'. DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . "mail",
        'core'. DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . "requisition"
    );

    foreach($array_paths as $path){
        $file = ROOT_DIR . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $class_name . ".php";        
        if(file_exists($file)){
            include $file;
        }
    }
}
spl_autoload_register('autoload');

// PHPMailer Autoload
include 'core/libs/mail/PHPMailerAutoload.php';

// Set the server default time zone
date_default_timezone_set("America/Bahia");

// Setting all necessary headers
Headers::getAllHeaders();

// Creating response object
$response = new Response();

// Verb variable
$verb = strtoupper($_SERVER['REQUEST_METHOD']);