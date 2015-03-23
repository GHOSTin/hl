<?php

use domain\session;
use domain\user;

class session_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->time = time();
    $this->session = new session();
  }

  public function test_new_instance(){
    $user = new user();
    $session = session::new_instance($user, '192.168.0.1');
    $this->assertGreaterThanOrEqual($this->time, $session->get_time());
    $this->assertSame($user, $session->get_user());
    $this->assertEquals('192.168.0.1', $session->get_ip());
  }
}