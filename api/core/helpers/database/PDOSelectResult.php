<?php

/**
 * This object must be returned by any select for an IDAO method
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
abstract class PDOSelectResult {
    
    const EXECUTED_INDEX = "executed";
    const RESULT_INDEX = "result";

    /**
     * This method is used to standardize the return of a select query
     * @param bool $executed If the statement has been executed with success
     * @param PDOStatement $stmt The statement object
     * @param bool $multipleRows If the return can be multiple rows
     * @return array Return an standardize array with the data selected
     */
    public static function returnArray(bool $executed, PDOStatement $stmt, bool $multipleRows = false) : array {
        $arrayReturn = $multipleRows ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC);
        return array(
            PDOSelectResult::EXECUTED_INDEX => $executed,
            PDOSelectResult::RESULT_INDEX   => ($executed ? $arrayReturn : $stmt->errorInfo())
        );
    }

    /**
     * This method is used to standardize an error in select
     * @param array $result PDOSelectResult array
     * @return Response Return a standardize array with the error description
     */
    public static function defaultSelectResultError(array $result) : Response{
        http_response_code(500);
        $response = new Response();
        $response->setStatus(ResponseStatus::FAILED_STATUS);
        $response->setMessage("Error in select step.");
        $response->setData(PDOErrorInfo::returnError(
            $result[PDOSelectResult::RESULT_INDEX][DbConnection::ERROR_INFO_CODE_INDEX],
            $result[PDOSelectResult::RESULT_INDEX][DbConnection::ERROR_INFO_MSG_INDEX]
        ));
        return $response;
    }

}
