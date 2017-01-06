<?php

/**
 * Class responsible to generate the hashs of application
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class ApplicationSecurity{

    /**
     * Encrypted the password with sha1.
     * @param string $password Text plain password
     * @return string Password hash
     */
    public static function generatePasswordHash(string $password) : string {
        return sha1("SDGdfhd94#$@#&#$#%@&her" . $password . "%&*hrehreth45&%$&$57");
    }

    /**
     * Generate a hash from user authentication
     * @param int $userId Id of user
     * @return string Return the hash
     */
    public static function generateAuthHash(int $userId) : string {
        return sha1("DGS23%@#@#00" . time() . $userId . "FDSDSG23523@#%#@236b");
    }
    
    /**
     * Verify if the token sended by the endpoint is valid
     * @param int $userId id of user
     */
    public static function verifyUserToken(int $userId){
        
        $response = new Response();
        
        // Verify if Authorization Header exists
        $headers = apache_request_headers();
        if(!isset($headers['Authorization'])){
            $response->setStatus(ResponseStatus::FAILED_STATUS);
            $response->setMessage("Missing Authorization header");
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        $authorization = $headers['Authorization'];
        
        // Select the Authorization of user
        $resultAuth = AuthDAO::getInstance()->selectById($userId);
        
        // Catch any errors in result
        if(!$resultAuth[PDOSelectResult::EXECUTED_INDEX]){
            die(json_encode($resultAuth[PDOSelectResult::RESULT_INDEX], JSON_UNESCAPED_UNICODE));
        }
        
        // Verify if the authorization is valid for the user
        if(!$resultAuth[PDOSelectResult::RESULT_INDEX]){
            $response->setStatus(ResponseStatus::FAILED_STATUS);
            $response->setMessage("Invalid Authorization code");
            $response->setData(PDOErrorInfo::returnError(
                $resultAuth[PDOSelectResult::RESULT_INDEX][DbConnection::ERROR_INFO_CODE_INDEX],
                $resultAuth[PDOSelectResult::RESULT_INDEX][DbConnection::ERROR_INFO_MSG_INDEX]
            ));
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        
        // Verify if the authorization sended by endpoint its the same as the code in database
        if($authorization != $resultAuth[PDOSelectResult::RESULT_INDEX]["code"]){
            $response->setStatus(ResponseStatus::FAILED_STATUS);
            $response->setMessage("Authentication code does not match");
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        
        // Verify the validity of authorization code
        $actualDateTime = new DateTime();
        $tokenExpiration = new DateTime($resultAuth[PDOSelectResult::RESULT_INDEX]["expiration"]);
        
        if($actualDateTime->getTimestamp() > $tokenExpiration->getTimestamp()){
            $response->setStatus(ResponseStatus::FAILED_STATUS);
            $response->setMessage("Expired Authorization code");
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        }

    }

}