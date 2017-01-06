<?php

/**
 * This object must be returned by any select for an IDAO method
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
abstract class PDOSelectResult {
    
    const EXECUTED_INDEX = "executed";
    const RESULT_INDEX = "result";
    
    public static function returnArray(bool $executed, PDOStatement $stmt) : array {
        return array(
            PDOSelectResult::EXECUTED_INDEX => $executed,
            PDOSelectResult::RESULT_INDEX   => ($executed ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->errorInfo())
        );
    }
    
}
