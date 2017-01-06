<?php

/**
 * This class is used to return a error in transaction
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
abstract class PDOErrorInfo{

    public static function returnError($errorCode, $errorDescription) : array{
        return [
            "errorCode" => $errorCode,
            "errorDescription" => $errorDescription
        ];
    }

}