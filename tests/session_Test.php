<?php

use \domain\session;
use \domain\user;

class session_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->session = new session();
  }

  public function test_time_1(){
    $this->session->set_time(156);
    $this->assertEquals(156, $this->session->get_time());
  }

  public function test_user_1(){
    $user = new user();
    $this->session->set_user($user);
    $this->assertSame($user, $this->session->get_user());
  }

  public function test_ip_1(){
    $this->session->set_ip('192.168.0.101');
    $this->assertEquals('192.168.0.101', $this->session->get_ip());
  }
}