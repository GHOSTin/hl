<?php namespace tests\domain;

use domain\query2comment;
use domain\query;
use domain\user;
use PHPUnit_Framework_TestCase;

class query2comment_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->comment = new query2comment();
  }

  public function test_set_time(){
    $this->comment->set_time(1396893600);
    $this->assertEquals(1396893600, $this->comment->get_time());
  }

  public function test_set_query(){
    $query = new query();
    $this->comment->set_query($query);
    $this->assertSame($query, $this->comment->get_query());
  }

  public function test_set_user(){
    $user = new user();
    $this->comment->set_user($user);
    $this->assertSame($user, $this->comment->get_user());
  }

  public function test_set_message(){
    $this->comment->set_message('Привет');
    $this->assertEquals('Привет', $this->comment->get_message());
  }
}