<?php

/**
 * Description of Response
 *
 * @author cassiano.vellames
 */
final class Response implements JsonSerializable{
    private $status;
    private $message;
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
     * Encoded object 
     */
    public function jsonSerialize() {
        return [
            "status" => $this->getStatus(),
            "message" => $this->getMessage(),
            "data" => $this->getData()
        ];
    }
}
