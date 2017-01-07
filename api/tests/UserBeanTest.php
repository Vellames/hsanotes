<?php

include "autoload.php";
/**
 * Test case of UserBean class
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class UserBeanTest extends PHPUnit_Framework_TestCase {

    private $userBean;

    public function setUp(){
        $this->userBean = new UserBean();
    }

    public function tearDown() {
        $this->userBean = null;
    }

    /**
     * Testing ID get and set
     */
    public function testId(){
        $this->userBean->setId(100);
        $this->assertEquals(100, $this->userBean->getId());
    }

    /**
     * Testing Name get and set
     */
    public function testName(){
        $this->userBean->setName("High Stakes Academy");
        $this->assertEquals("High Stakes Academy", $this->userBean->getName());
    }

    /**
     * Testing Email get and set
     */
    public function testEmail(){
        $this->userBean->setEmail("cassiano@highstakes.com.br");
        $this->assertEquals("cassiano@highstakes.com.br", $this->userBean->getEmail());
    }

    /**
     * Testing Password get and set
     */
    public function testPassword(){
        $this->userBean->setPassword("123456");
        $this->assertEquals("123456", $this->userBean->getPassword());
    }

    /**
     * Testing the Created get and set
     */
    public function testCreated(){
        $dateTime = new DateTime("2017-01-08 17:30:00");
        $this->userBean->setCreated($dateTime);
        $this->assertEquals("2017-01-08 17:30:00", $this->userBean->getCreated()->format("Y-m-d H:i:s"));
    }

    /**
     * Testing the Modified get and set
     */
    public function testModified(){
        $dateTime = new DateTime("2050-01-08 11:30:00");
        $this->userBean->setModified($dateTime);
        $this->assertEquals("2050-01-08 11:30:00", $this->userBean->getModified()->format("Y-m-d H:i:s"));
    }

    /**
     * Testing PasswordRecoveryHash get and set
     */
    public function testPasswordRecoveryHash(){
        $hash = ApplicationSecurity::generatePasswordRecoveryHash(1);
        $this->userBean->setPasswordRecoveryHash($hash);
        $this->assertEquals($hash, $this->userBean->getPasswordRecoveryHash());
    }

    /**
     * Testing PasswordRecoveryExpiration get and set
     */
    public function testPasswordRecoveryExpiration(){
        $dateTime = new DateTime("2020-03-22 11:55:22");
        $this->userBean->setPasswordRecoveryExpiration($dateTime);
        $this->assertEquals("2020-03-22 11:55:22", $this->userBean->getPasswordRecoveryExpiration()->format("Y-m-d H:i:s"));
    }

    /**
     * Testing the constructor of class
     */
    public function testConstructor(){
        $id = 1;
        $name = "Cassiano";
        $email = "c.vellames@outlook.com";
        $password = ApplicationSecurity::generatePasswordHash("1234567890");
        $created = new DateTime("2016-01-07 17:45:00");
        $modified = new DateTime("2016-01-07 17:45:01");

        $userBean = new UserBean($id, $name, $email, $password, $created, $modified);

        $this->assertEquals($id, $userBean->getId());
        $this->assertEquals($name, $userBean->getName());
        $this->assertEquals($email, $userBean->getEmail());
        $this->assertEquals($password, $userBean->getPassword());
        $this->assertEquals($created->format("Y-m-d H:i:s"), $userBean->getCreated()->format("Y-m-d H:i:s"));
        $this->assertEquals($modified->format("Y-m-d H:i:s"), $userBean->getModified()->format("Y-m-d H:i:s"));
    }

}

