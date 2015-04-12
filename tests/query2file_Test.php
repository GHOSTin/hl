<?php

use domain\file;
use domain\query;
use domain\query2file;
use domain\user;

class query2file_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->user = new user();
    $this->query = new query();
    $this->path = '20150410/249d2c7dbd66604b90938aa1d7093f711ec4b77e.jpg';
    $this->time = time();
    $this->name = 'image.jpg';
  }

  public function test_file(){
    $file = new file($this->user, $this->path, $this->time, $this->name);
    $q2f = new query2file($this->query, $file);
    $this->assertSame($this->user, $q2f->get_user());
    $this->assertSame($this->query, $q2f->get_query());
    $this->assertEquals($this->time, $q2f->get_time());
    $this->assertEquals($this->path, $q2f->get_path());
    $this->assertEquals($this->name, $q2f->get_name());
  }
}