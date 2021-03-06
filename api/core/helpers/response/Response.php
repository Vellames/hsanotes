<?php

/**
 * This class is used to return the data to the user in all requisitions
 * All communication with the endpoint must be made with a Response object
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
final class Response implements JsonSerializable{

    /**
     * @var string Status of response, preferably use the ResponseStatus 'enum' to set this attribute
     */
    private $status;

    /**
     * @var string Message of the requisition
     */
    private $message;

    /**
     * @var mixed Data returned to the endpoint
     */
    private $data;
    
    public function getStatus() : string {
        return $this->status;
    }

    public function getMessage() : string {
        return $this->message;
    }

    public function getData() {
        return $this->data;
    }

    public function setStatus(string $status) {
        if(!in_array($status, ResponseStatus::getStatus())){
            throw new InvalidArgumentException("Unknown status type");
        }
        $this->status = $status;
    }

    public function setMessage(string $message) {
        $this->message = $message;
    }

    public function setData($data) {
        $this->data = $data;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize(){
        return [
            "status" => $this->getStatus(),
            "message" => $this->getMessage(),
            "data" => $this->getData()
        ];
    }
}
