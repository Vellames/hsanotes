<?php

/**
 * Call the login method in the controller of User
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */

include "../autoload.php";

$postData = json_decode(file_get_contents("php://input"), true);

$userController = new UserController();
echo json_encode($userController->login($postData), JSON_UNESCAPED_UNICODE);