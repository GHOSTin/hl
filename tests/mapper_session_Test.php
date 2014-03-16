<?php

class mapper_session_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->user = new data_user();
    $this->session = new data_session();
    $this->session->set_user($this->user);
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
  }

  public function test_insert_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $mapper = new mapper_session($this->pdo);
    $mapper->insert($this->session);
  }

  public function test_insert_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $mapper = new mapper_session($this->pdo);
    $mapper->insert($this->session);
  }
}