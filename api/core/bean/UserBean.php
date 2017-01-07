<?php

/**
 * This class is a abstraction of an user
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

    /**
     * @var string Hash with the last solicitation of password recovery
     */
    private $passwordRecoveryHash;

    /**
     * @var DateTime Date Time of the last solicitation of password recovery
     */
    private $passwordRecoveryExpiration;

    /**
     * All parameters are optional, so the object can be constructed in several ways
     * @param int|null $id
     * @param string|null $name
     * @param string|null $email
     * @param string|null $password
     * @param DateTime|null $created
     * @param DateTime|null $modified
     * @param string|null $passwordRecoveryHash
     * @param DateTime|null $passwordRecoveryExpiration
     */
    function __construct(int $id = null, string $name = null, string $email = null, string $password = null, DateTime $created = null, DateTime $modified = null, string $passwordRecoveryHash = null, DateTime $passwordRecoveryExpiration = null){
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->created = $created;
        $this->modified = $modified;
        $this->passwordRecoveryHash = $passwordRecoveryHash;
        $this->passwordRecoveryExpiration = $passwordRecoveryExpiration;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function getCreated(): DateTime {
        return $this->created;
    }

    public function setCreated(DateTime $created) {
        $this->created = $created;
    }

    public function getModified(): DateTime {
        return $this->modified;
    }

    public function setModified(DateTime $modified) {
        $this->modified = $modified;
    }

    public function getPasswordRecoveryHash(): string {
        return $this->passwordRecoveryHash;
    }

    public function setPasswordRecoveryHash(string $passwordRecoveryHash) {
        $this->passwordRecoveryHash = $passwordRecoveryHash;
    }

    public function getPasswordRecoveryExpiration(): DateTime {
        return $this->passwordRecoveryExpiration;
    }

    public function setPasswordRecoveryExpiration(DateTime $passwordRecoveryExpiration) {
        $this->passwordRecoveryExpiration = $passwordRecoveryExpiration;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize() {
        // Note: Password is not passed in the serialized object
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "created" => isset($this->created) ? $this->created->format("Y-m-d H:i:s") : null,
            "modified" => isset($this->modified) ? $this->modified->format("Y-m-d H:i:s") : null
        ];
    }

}
