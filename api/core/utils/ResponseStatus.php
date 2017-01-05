<?php

/**
 * Possible return values of field "status" from Response object
 * @author cassiano.vellames
 */
abstract class ResponseStatus{

    const SUCCEEDED_STATUS = "succeeded";
    const FAILED_STATUS = "failed";

    public static function getStatus() : array{
        return [
            ResponseStatus::SUCCEEDED_STATUS,
            ResponseStatus::FAILED_STATUS
        ];
    }
}
