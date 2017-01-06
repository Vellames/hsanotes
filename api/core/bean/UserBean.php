<?php

/**
 *
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
final class UserBean implements IBean, JsonSerializable{
    
    /**
     * @var int Id of User
     */
    private $id;
    
    /**
     * @var string name of user
     */
    private $name;
    
    /**
     * @var string email of user
     */
    private $email;
    
    /**
     * @var string password of user
     */
    private $password;
    
    /**
     * @var DateTime Date that the user has been created
     */
    private $created;
    
    /**
     * @var DateTime Date of the last modify in user
     */
    private $modified;
    
    function __construct(int $id = null, string $name = null, string $email = null, string $password = null, DateTime $created = null, DateTime $modified = null){
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->created = $created;
        $this->modified = $modified;
    }
    
    function getId() : int {
        return $this->id;
    }

    function getName() : string {
        return $this->name;
    }

    function getEmail() : string {
        return $this->email;
    }

    function getPassword() : string {
        return $this->password;
    }

    function getCreated() : DateTime{
        return $this->created;
    }

    function getModified() : DateTime{
        return $this->modified;
    }

    function setId(int $id) {
        $this->id = $id;
    }

    function setName(string $name) {
        $this->name = $name;
    }

    function setEmail(string $email) {
        $this->email = $email;
    }

    function setPassword(string $password) {
        $this->password = $password;
    }

    function setCreated(DateTime $created) {
        $this->created = $created;
    }

    function setModified(DateTime $modified) {
        $this->modified = $modified;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "created" => isset($this->created) ? $this->created->format("Y-m-d H:i:s") : "",
            "modified" => isset($this->modified) ? $this->modified->format("Y-m-d H:i:s") : ""
        ];
    }

}
