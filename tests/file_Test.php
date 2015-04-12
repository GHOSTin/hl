<?php

use domain\file;
use domain\user;

class file_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->user = new user();
    $this->path = '20150410/249d2c7dbd66604b90938aa1d7093f711ec4b77e.jpg';
    $this->time = time();
    $this->name = 'image.jpg';
  }

  public function test_file(){
    $file = new file($this->user, $this->path, $this->time, $this->name);
    $this->assertSame($this->user, $file->get_user());
    $this->assertEquals($this->time, $file->get_time());
    $this->assertEquals($this->path, $file->get_path());
    $this->assertEquals($this->name, $file->get_name());
  }

  public function test_name(){
    $this->setExpectedException('DomainException');
    $file = new file($this->user, $this->path, $this->time, str_repeat('afqkafqk', 32));
  }

  public function test_path(){
    $this->setExpectedException('DomainException');
    $file = new file($this->user, str_repeat('afqkafqk', 32), $this->time, $this->name);
  }
}