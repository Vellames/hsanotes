<?php

/**
 * This class contain business rule of Auth mechanism
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class AuthController extends Controller{

    /**
    * The parent construct initialize the $response variable
    */
    function __construct(){
        parent::__construct();
    }

    /**
     * This method is called when a get requisition is received. If Id is passed this method return the register
     * whit the passed id, if not, return all registers of table.
     * This method must be called when the user wants retrieve the data from database
     * @param null | int $id Id of object
     * @return Response Object with response data
     */
    public function getRequisition($id = null): Response
    {
        // TODO: Implement getRequisition() method.
    }

    /**
     * Insert a new Auth register in database
     * @param $postData mixed Id of user
     * @return Response Object with response data
     */
    public function postRequisition($postData): Response{

        $result = AuthDAO::getInstance()->insert($postData);

        $errorCode = $result[DbConnection::ERROR_INFO_CODE_INDEX];

        $successMsg = "Auth register inserted with success";
        $failMsg = "Error to insert the Auth Register (Code: {$result})";

        $this->response->setStatus($errorCode == 0 ? ResponseStatus::SUCCEEDED_STATUS : ResponseStatus::FAILED_STATUS);
        $this->response->setMessage($errorCode == 0 ? $successMsg : $failMsg);
        if($errorCode != 0){
            $this->response->setData(PDOErrorInfo::returnError(
                $result[DbConnection::ERROR_INFO_CODE_INDEX],
                $result[DbConnection::ERROR_INFO_MSG_INDEX]
            ));
        }

        return $this->response;
    }

    /**
     * This method is called when a put or patch requisition is received
     * This method must be called when the user wants update a register in database
     * @param $putData mixed Data to update in database
     * @return Response Object with response data
     */
    public function putRequisition($putData): Response
    {
        // TODO: Implement putRequisition() method.
    }

    /**
     * This method is called when a delete requisition is received
     * This method must be called when the user wants delete a register in database
     * @param $id int  Id to delete
     * @return Response Object with response data
     */
    public function deleteRequisition($id): Response
    {
        // TODO: Implement deleteRequisition() method.
    }

    /**
     * Add 10 minutes of expiration time for a token
     * @return DateTime Return the new DateTime Expiration
     */
    private function renewAuthExpiration(){
        $date = new DateTime();
        $date->add(date_interval_create_from_date_string("10 minutes"));
        return $date;
    }
}