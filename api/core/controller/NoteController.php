<?php

/**
 * This class contain business rule of Note
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class NoteController extends Controller{
    
    /**
     * The parent construct initialize the $response variable
     */
    function __construct(){
        parent::__construct();
    }

    /**
     * Load a note
     * @param int $id Id to load the note
     * @return Response Response of requisition
     */
    public function getRequisition(int $id): Response {
        $resultGet = NoteDAO::getInstance()->selectById($id);

        // Catch any errors in result
        if(!$resultGet[PDOSelectResult::EXECUTED_INDEX]){
            return PDOSelectResult::defaultSelectResultError($resultGet);
        }

        // Verify if note exists
        if(!$resultGet[PDOSelectResult::RESULT_INDEX]){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("The note dont exists");
            return $this->response;
        }

        // Using the RESULT INDEX to recovery the data
        $resultGet = $resultGet[PDOSelectResult::RESULT_INDEX];
        $userBean = new UserBean($_GET["user_id"]);
        $noteBean = new NoteBean(
            $resultGet["id"],
            $userBean,
            $resultGet["title"],
            $resultGet["description"],
            new DateTime($resultGet["created"]),
            new DateTime($resultGet["modified"])
        );

        $token = (new UserController)->renewAuthToken($userBean);

        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("Note listed with success");
        $this->response->setData(array(
            "note" => $noteBean,
            "token" => $token
        ));

        return $this->response;
    }

    /**
     * Add a new note in database
     * @param array $postData Params sent by the endpoint
     * @return Response Return one response with the status of solicitation
     */
    public function postRequisition(array $postData): Response {

        // Creating the necessary objects
        $userBean = new UserBean($postData["user_id"]);
        $noteBean = new NoteBean(
            NULL,
            $userBean,
            $postData["title"],
            $postData["description"],
            new DateTime(),
            new DateTime()
        );

        $insertResult = NoteDAO::getInstance()->insert($noteBean);

        // Check errors in DML command
        if($insertResult[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            return PDOErrorInfo::defaultDMLResultError($insertResult);
        }

        // Update the id property of notebean
        $noteBean->setId(DbConnection::getInstance()->lastInsertId());

        // Renew the token
        $token = (new UserController)->renewAuthToken($userBean);

        // Send response data
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("Note inserted with success");
        $this->response->setData(array(
            "note"  => $noteBean,
            "token" => $token
        ));

        return $this->response;
    }

    /**
     * Update a note in database
     * @param array $putData Data sent by the endpoint
     * @return Response Return
     */
    public function putRequisition(array $putData): Response {

        //Get note from database
        $resultNote = NoteDAO::getInstance()->selectById($putData["id"]);

        // Catch any errors in result
        if(!$resultNote[PDOSelectResult::EXECUTED_INDEX]){
            return PDOSelectResult::defaultSelectResultError($resultNote);
        }

        // Verify if note exists
        if(!$resultNote[PDOSelectResult::RESULT_INDEX]){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("The note dont exists");
            return $this->response;
        }

        // Create the necessary objects
        $userBean = new UserBean($putData["user_id"]);
        $noteBean = new NoteBean(
            $putData["id"],
            $userBean,
            $putData["title"],
            $putData["description"],
            new DateTime($resultNote[PDOSelectResult::RESULT_INDEX]["created"]),
            new DateTime()
        );

        //Edit the note
        $result = NoteDAO::getInstance()->update($noteBean);

        // Verify DML erros
        if($result[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            return PDOErrorInfo::defaultDMLResultError($result);
        }

        // Renew the token
        $token = (new UserController)->renewAuthToken($userBean);

        // Send response data
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("Note updated with success");
        $this->response->setData(array(
            "note"  => $noteBean,
            "token" => $token
        ));

        return $this->response;
    }

    /**
     * Delete a note of an note
     * @param int $id Id to delete
     * @return Response Response of requisition
     */
    public function deleteRequisition(int $id): Response {

        // Delete the note
        $deleteResult = NoteDAO::getInstance()->deleteById($id);

        // Verify DML erros
        if($deleteResult[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            return PDOErrorInfo::defaultDMLResultError($deleteResult);
        }

        // Renew the token
        $token = (new UserController)->renewAuthToken(new UserBean($_GET["user_id"]));

        // Send the response data
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("Note deleted with success");
        $this->response->setData(array(
            "token" => $token
        ));

        return $this->response;
    }

    /**
     * Load a list of notes of the user
     * @param int $userId User id that wants the list
     * @param string|null $order Field to order the
     * @param string|null $orderType If the order is asc or desc
     * @param int|null $page Page number to load (Pagination)
     * @param int|null $registersByPage Registers for page
     * @return Response Return the list of notes in a response object
     */
    public function selectNotesByUser(int $userId, string $order = null, string $orderType = null, int $page = null, int $registersByPage = null) : Response {
        $result = NoteDAO::getInstance()->selectNotesByUser($userId, $order, $orderType, $page, $registersByPage);

        // Catch any errors in result
        if(!$result[PDOSelectResult::EXECUTED_INDEX]){
            return PDOSelectResult::defaultSelectResultError($result);
        }

        // Using the RESULT INDEX to recovery the data
        $result = $result[PDOSelectResult::RESULT_INDEX];
        $userBean = new UserBean($_GET["user_id"]);

        // Populating the array return with the notes
        $arrayReturn = null;
        foreach($result as $note){
            $arrayReturn[] = new NoteBean(
                $note["id"],
                $userBean,
                $note["title"],
                $note["description"],
                new DateTime($note["created"]),
                new DateTime($note["modified"])
            );
        }

        // Renew the token
        $token = (new UserController)->renewAuthToken($userBean);

        // Send the response
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("Notes listed with success");
        $this->response->setData(array(
            "notes" => $arrayReturn,
            "token" => $token
        ));

        return $this->response;
    }
}
