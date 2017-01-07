<?php

/**
 * Call the renew method password method of user
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */

include "../autoload.php";

Verifier::verifyRequisitionVerb("POST", $verb);

$postData = json_decode(file_get_contents("php://input"), true);
Verifier::checkInvalidJSON();

$userController = new UserController();
echo json_encode($userController->renewPassword($postData), JSON_UNESCAPED_UNICODE);