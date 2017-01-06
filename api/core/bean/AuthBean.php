<?php

/**
 * This class is an abstraction of the security key implementation of the application
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
final class AuthBean implements IBean, JsonSerializable {

    /**
     * @var UserBean User to which it belongs the validation code
     */
    private $user;

    /**
     * @var string Validation code that must be passed in the next requisition
     */
    private $code;

    /**
     * @var DateTime Time of expiration of the validation code
     */
    private $expiration;

    public function __construct(UserBean $user = null, string $code = null , DateTime $expiration = null){
        $this->user = $user;
        $this->code = $code;
        $this->expiration = $expiration;
    }

    public function getUser() : UserBean{
        return $this->user;
    }

    public function setUser(UserBean $user){
        $this->user = $user;
    }

    public function getCode() : string{
        return $this->code;
    }

    public function setCode(string $code){
        $this->code = $code;
    }

    public function getExpiration() : DateTime{
        return $this->expiration;
    }

    public function setExpiration(DateTime $expiration){
        $this->expiration = $expiration;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize(){
        // TODO: Implement jsonSerialize() method.
    }
}