<?php

/**
 * Unit test for the class AuthBean
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */

include "autoload.php";

class AuthBeanTest extends PHPUnit_Framework_TestCase{
    private $authBean;

    public function setUp() {
        $this->authBean = new AuthBean();
    }

    public function tearDown(){
        $this->authBean = null;
    }

    /**
     *  Testing user get and set
     */
    public function testUser(){
        $this->authBean->setUser(new UserBean());
        $this->assertInstanceOf("UserBean", $this->authBean->getUser());
    }

    /**
     * Testing code get and set
     */
    public function testCode(){
        $hash = ApplicationSecurity::generatePasswordRecoveryHash(1000);
        $this->authBean->setCode($hash);
        $this->assertEquals($hash, $this->authBean->getCode());
    }

    /**
     * Testing the expiration date get and set
     */
    public function testExpiration(){
        $date = new DateTime("2017-01-01 00:00:00");
        $this->authBean->setExpiration($date);
        $this->assertEquals($date->format("Y-m-d H:i:s"), $this->authBean->getExpiration()->format("Y-m-d H:i:s"));
    }

    /**
     * Testing the constructor
     */
    public function testConstructor(){
        $user = new UserBean();
        $hash = ApplicationSecurity::generatePasswordRecoveryHash(10001);
        $date = new DateTime("2018-01-01 00:00:00");

        $authBean = new AuthBean($user,$hash,$date);

        $this->assertInstanceOf("UserBean", $authBean->getUser());
        $this->assertEquals($hash, $authBean->getCode());
        $this->assertEquals($date->format("Y-m-d H:i:s"), $authBean->getExpiration()->format("Y-m-d H:i:s"));
    }
}
