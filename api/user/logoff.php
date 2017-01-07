<?php
/**
 * Call the logoff method in the controller of User
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */

include "../autoload.php";

$postData = json_decode(file_get_contents("php://input"), true);

ApplicationSecurity::verifyUserToken($postData["id"]);
$userController = new UserController();
echo json_encode($userController->logoff($postData["id"]), JSON_UNESCAPED_UNICODE);