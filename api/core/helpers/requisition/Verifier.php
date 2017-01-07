<?php

/**
 * This class does all the initial verification necessary for the API
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
abstract class Verifier {
    /**
     * Check if the last json decode has an error
     * @author Cassiano Vellames <c.vellames@outlook.com>
     * @since 1.0.0
     */
    public static function checkInvalidJSON(){
        if(json_last_error() !== JSON_ERROR_NONE){
            http_response_code(400);
            $response = new Response();
            $response->setStatus(ResponseStatus::FAILED_STATUS);
            $response->setMessage("Invalid json. Verify the syntax");
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Verify if the requisition verb is the same of the necessary verb for a requisition
     * @param string $necessaryVerb
     * @param string $requisitionVerb
     */
    public static function verifyRequisitionVerb(string $necessaryVerb, string $requisitionVerb){
        if(strtoupper($necessaryVerb) != strtoupper($requisitionVerb)){
            http_response_code(400);
            $response = new Response();
            $response->setStatus(ResponseStatus::FAILED_STATUS);
            $response->setMessage("Invalid verb requisition. This requisition must use the verb '{$necessaryVerb}'.");
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Verify if the GET requisition has the id of user
     * @param string $idParamName Name of user id param in get requisition
     */
    public static function verifyURLUserIdParam(string $idParamName){
        if(!isset($_GET[$idParamName])){
            http_response_code(400);
            $response = new Response();
            $response->setStatus(ResponseStatus::FAILED_STATUS);
            $response->setMessage("Invalid requisition. '{$idParamName}' param not sent.");
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Verify if the user id has been sent in the raw body
     * @param string $idParamName Name of user id param in the requisition
     * @param array $rawData Raw data sent by the endpoint
     */
    public static function verifyRawUserIdParam(string $idParamName, array $rawData){
        if(!isset($rawData[$idParamName])){
            http_response_code(400);
            $response = new Response();
            $response->setStatus(ResponseStatus::FAILED_STATUS);
            $response->setMessage("Invalid requisition. '{$idParamName}' param not sent.");
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        }
    }
}