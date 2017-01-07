<?php

/**
 * Call the selectNotesByUser method in the controller of Notes
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */

include "../autoload.php";

Verifier::verifyRequisitionVerb("GET", $verb);
Verifier::verifyURLUserIdParam("user_id");
ApplicationSecurity::verifyUserToken($_GET["user_id"]);

$noteController = new NoteController();
echo json_encode($noteController->selectNotesByUser(
    $_GET["user_id"],
    isset($_GET["order"]) ? $_GET["order"] : null, // Isset verification to avoid notice message
    isset($_GET["order_type"]) ? $_GET["order_type"] : null,
    isset($_GET["page"]) ? $_GET["page"] : null,
    isset($_GET["registers_by_page"]) ? $_GET["registers_by_page"] : null
    ), JSON_UNESCAPED_UNICODE);