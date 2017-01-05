<?php

/**
 *
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
final class UserBean implements IBean, JsonSerializable{
    
    private $id;
    private $name;
    private $email;
    private $password;
    private $created;
    private $modified;
    
    function __construct(string $name, string $email, string $password, DateTime $created, DateTime $modified, int $id = null) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->created = $created;
        $this->modified = $modified;
        $this->id = $id;
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

    function setId(int $id = null) {
        $this->id = $id;
    }

    function setName(string $name) {
        echo "noome";
        var_dump($name);
        $this->name = $name;
        var_dump($this->name);
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
            "created" => $this->created->format("Y-m-d H:i:s"),
            "modified" => $this->modified->format("Y-m-d H:i:s")
        ];
    }

}
