<?php

use \domain\query2comment;
use \domain\query;

class query_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->query = new query();
  }

  public function test_add_comment_1(){
    $comment = new query2comment();
    $this->query->add_comment($comment);
    $this->assertSame($comment, $this->query->get_comments()[0]);
  }

  public function test_add_comment_2(){
    $this->setExpectedException('DomainException');
    $comment = new query2comment();
    $this->query->add_comment($comment);
    $this->query->add_comment($comment);
  }
}