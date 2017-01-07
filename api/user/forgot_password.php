<?php
/**
 * Call the forgot password method of user
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */

include "../autoload.php";

$userController = new UserController();
echo json_encode($userController->forgotPassword($_GET["email"]), JSON_UNESCAPED_UNICODE);