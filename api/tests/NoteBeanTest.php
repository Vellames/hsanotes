<?php

/**
 * Unit test for the class NoteBean
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */

include "autoload.php";

class NoteBeanTest extends  PHPUnit_Framework_TestCase{

    private $noteBean;

    public function setUp(){
        $this->noteBean = new NoteBean();
    }

    public function tearDown(){
        $this->noteBean = null;
    }

    /**
     * Testing the id get and set
     */
    public function testId(){
        $this->noteBean->setId(1);
        $this->assertEquals(1, $this->noteBean->getId());
    }

    /**
     * Testing the user get and set
     */
    public function testUser(){
        // verify instance
        $userBean = new UserBean();
        $this->assertInstanceOf("UserBean", $userBean);
    }

    /**
     * Testing the title get and set
     */
    public function testTitle(){
        $this->noteBean->setTitle("Title Test");
        $this->assertEquals("Title Test", $this->noteBean->getTitle());
    }

    /**
     * Testing the description get and set
     */
    public function testDescription(){
        $this->noteBean->setDescription("Lorem ipsum");
        $this->assertEquals("Lorem ipsum", $this->noteBean->getDescription());
    }

    /**
     * Testing the created get and set
     */
    public function testCreated(){
        $dateTime = new DateTime("2020-11-11 22:52:26");
        $this->noteBean->setCreated($dateTime);
        $this->assertEquals("2020-11-11 22:52:26", $this->noteBean->getCreated()->format("Y-m-d H:i:s"));
    }

    /**
     * Testing the modified get and set
     */
    public function testModified(){
        $dateTime = new DateTime("2015-12-05 23:11:22");
        $this->noteBean->setModified($dateTime);
        $this->assertEquals("2015-12-05 23:11:22", $this->noteBean->getModified()->format("Y-m-d H:i:s"));
    }

    /**
     * Testing the constructor
     */
    public function testConstructor(){
        $id = 10000;
        $user = new UserBean();
        $title = "Test Case";
        $description = "Description of Test Case";
        $created = new DateTime("1990-11-22 11:22:11");
        $modified = new DateTime("1991-11-11 22:11:22");

        $noteBean = new NoteBean($id, $user, $title, $description, $created, $modified);

        $this->assertEquals($id, $noteBean->getId());
        $this->assertInstanceOf("UserBean", $noteBean->getUser());
        $this->assertEquals($title, $noteBean->getTitle());
        $this->assertEquals($description, $noteBean->getDescription());
        $this->assertEquals($created->format("Y-m-d H:i:s"), $noteBean->getCreated()->format("Y-m-d H:i:s"));
        $this->assertEquals($modified->format("Y-m-d H:i:s"), $noteBean->getModified()->format("Y-m-d H:i:s"));

    }

}