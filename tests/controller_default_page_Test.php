<?php

use \boxxy\classes\di;

class controller_default_page_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->request = new model_request();
  }

  public function test_private_show_default_page(){
    controller_default_page::private_show_default_page($this->request);
  }
}