<?php

header('Content-Type: application/json');

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

$class = new $className;

// Verifying verb to send to correct method
switch ($verb) {
    case "GET":
        break;
    case "POST":
        //$postData = json_decode(file_get_contents("php://input"), true);
        echo json_encode($class->postRequisition($_POST), JSON_UNESCAPED_UNICODE);
        break;
    case "PUT" || "PATCH":
        break;
    case "DELETE":
        break;
    default:
        $response->setStatus(ResponseStatus::FAILED_STATUS);
        $response->setMessage("Unsupported verb type: '{$verb}'");
        die(json_encode($response, JSON_UNESCAPED_UNICODE));
}