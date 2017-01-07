<?php

/**
 * This is the dispatcher of user
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */

include "../autoload.php";

// Creating response object
$response = new Response();

// Verb variable
$verb = strtoupper($_SERVER['REQUEST_METHOD']);

// Verify verb to call the correct method
$class = new NoteController();
switch ($verb) {
    case "GET":
        ApplicationSecurity::verifyUserToken($_GET["user_id"]);
        echo json_encode($class->getRequisition($_GET["id"]), JSON_UNESCAPED_UNICODE);
        break;
    case "POST":
        $postData = json_decode(file_get_contents("php://input"), true);
        ApplicationSecurity::verifyUserToken($postData["user_id"]);
        echo json_encode($class->postRequisition($postData), JSON_UNESCAPED_UNICODE);
        break;
    case "PUT":
        $postData = json_decode(file_get_contents("php://input"), true);
        ApplicationSecurity::verifyUserToken($postData["user_id"]);
        echo json_encode($class->putRequisition($postData), JSON_UNESCAPED_UNICODE);
        break;
    case "DELETE":
        ApplicationSecurity::verifyUserToken($_GET["user_id"]);
        echo json_encode($class->deleteRequisition($_GET["id"]), JSON_UNESCAPED_UNICODE);
        break;
    default:
        $response->setStatus(ResponseStatus::FAILED_STATUS);
        $response->setMessage("Unsupported verb type: '{$verb}'");
        die(json_encode($response, JSON_UNESCAPED_UNICODE));
}