<?php

class data_department_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->departmnent = new data_department();
  }

  public function test_set_id_1(){
    $this->departmnent->set_id(125);
    $this->assertEquals(125, $this->departmnent->get_id());
  }

  public function test_set_id_2(){
    $this->setExpectedException('DomainException');
    $this->departmnent->set_id(0);
  }

  public function test_set_id_3(){
    $this->setExpectedException('DomainException');
    $this->departmnent->set_id(256);
  }

  public function test_set_id_4(){
    $this->setExpectedException('DomainException');
    $this->departmnent->set_id(-125);
  }

  public function test_set_name_1(){
    $this->departmnent->set_name('Участок');
    $this->assertEquals('Участок', $this->departmnent->get_name());
    $this->departmnent->set_name('Участок №1');
    $this->assertEquals('Участок №1', $this->departmnent->get_name());
  }

  public function test_set_name_2(){
    $this->setExpectedException('DomainException');
    $this->departmnent->set_name('Уч');
  }

  public function test_set_name_3(){
    $this->setExpectedException('DomainException');
    $this->departmnent->set_name(str_repeat('Уч', 10));
  }

  public function test_set_name_4(){
    $this->setExpectedException('DomainException');
    $this->departmnent->set_name('English');
  }

  public function test_set_status_1(){
    $this->departmnent->set_status('active');
    $this->assertEquals('active', $this->departmnent->get_status());
    $this->departmnent->set_status('deactive');
    $this->assertEquals('deactive', $this->departmnent->get_status());
  }

  public function test_set_status_2(){
    $this->setExpectedException('DomainException');
    $this->departmnent->set_status('truefalse');
  }
}