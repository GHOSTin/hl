<?php

class controller_about_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->request = new model_request();
  }

  public function test_private_show_default_page(){
    $this->assertNull(controller_about::private_show_default_page($this->request));
  }
}