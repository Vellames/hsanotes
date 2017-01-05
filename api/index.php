<?php

/**
 * This is the dispatcher. All requisitions pass here
 * 
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */

// Load class
error_reporting(E_ERROR | E_PARSE);
function __autoload($class){
    include_once "core/utils/{$class}.php";
    include_once "core/interface/{$class}.php";
    include_once "core/config/{$class}.php";
    include_once "core/libs/personal/{$class}.php";
    include_once "core/controller/{$class}.php";
    include_once "core/bean/{$class}.php";
    include_once "core/dao/{$class}.php";
}

// Setting all necessary headers
//Headers::getAllHeaders();

// Creating response object
$response = new Response();

// Get the variables
if(!isset($_GET["controller"])){
    $response->setStatus(ResponseStatus::FAILED_STATUS);
    $response->setMessage("Missing basic param 'controller'");
    die(json_encode($response, JSON_UNESCAPED_UNICODE));
}
$controller = $_GET["controller"];
$verb = $_SERVER['REQUEST_METHOD'];

// Verifying if class exists
$className = ($controller . "Controller");
if(!class_exists($className)){
    $response->setStatus(ResponseStatus::FAILED_STATUS);
    $response->setMessage("Unknown controller type: '{$className}'");
    die(json_encode($response, JSON_UNESCAPED_UNICODE));
}

// If an action is passed, the dispacher will call the specific method called, else, the verb equivalent method will be called
$class = new $className;
switch ($verb) {
    case "GET":
        if(isset($_GET["action"])){
            //TODO: Call the method
        } else {
            //TODO: Call the restful method
        }
        break;
    case "POST":
        $postData = json_decode(file_get_contents("php://input"), true);
        if(isset($postData["action"])){
            //TODO: Call the method
        } else {
            echo json_encode($class->postRequisition($postData), JSON_UNESCAPED_UNICODE);
        }
        break;
    case "PUT" || "PATCH":
        $postData = json_decode(file_get_contents("php://input"), true);
        if(isset($postData["action"])){
            //TODO: Call the method
        } else {
            //TODO: Call the restful method
        }
        break;
    case "DELETE":
         if(isset($_GET["action"])){
            //TODO: Call the method
        } else {
            //TODO: Call the restful method
        }
        break;
    default:
        $response->setStatus(ResponseStatus::FAILED_STATUS);
        $response->setMessage("Unsupported verb type: '{$verb}'");
        die(json_encode($response, JSON_UNESCAPED_UNICODE));
}

