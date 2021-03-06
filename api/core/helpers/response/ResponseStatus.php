<?php

/**
 * Possible return values of field "status" from Response object
 * @author cassiano.vellames
 */
abstract class ResponseStatus{

    /**
     * Possible response status
     */
    const SUCCEEDED_STATUS = "succeeded";
    const FAILED_STATUS = "failed";

    /**
     * @return array Return an array with all possible status
     */
    public static function getStatus() : array{
        return [
            ResponseStatus::SUCCEEDED_STATUS,
            ResponseStatus::FAILED_STATUS
        ];
    }
}
