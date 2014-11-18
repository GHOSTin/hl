<?php

class data_city_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->city = new data_city();
  }

  public function test_set_id_1(){
    $this->city->set_id(125);
    $this->assertEquals(125, $this->city->get_id());
  }

  public function test_set_id_2(){
    $this->setExpectedException('DomainException');
    $this->city->set_id(0);
  }

  public function test_set_id_3(){
    $this->setExpectedException('DomainException');
    $this->city->set_id(256);
  }

  public function test_set_id_4(){
    $this->setExpectedException('DomainException');
    $this->city->set_id(-125);
  }

  public function test_set_name_1(){
    $this->city->set_name('Первоуральск');
    $this->assertEquals('Первоуральск', $this->city->get_name());
    $this->city->set_name('Ростов-на-дону');
    $this->assertEquals('Ростов-на-дону', $this->city->get_name());
    $this->city->set_name('Новое Село');
    $this->assertEquals('Новое Село', $this->city->get_name());
  }
  public function test_set_name_2(){
    $this->setExpectedException('DomainException');
    $this->city->set_name('Н');
  }

  public function test_set_name_3(){
    $this->setExpectedException('DomainException');
    $this->city->set_name(str_repeat('На-', 7));
  }

  public function test_set_name_4(){
    $this->setExpectedException('DomainException');
    $this->city->set_name('English');
  }

  public function test_set_status_1(){
    $this->city->set_status('true');
    $this->assertEquals('true', $this->city->get_status());
    $this->city->set_status('false');
    $this->assertEquals('false', $this->city->get_status());
  }

  public function test_set_status_2(){
    $this->setExpectedException('DomainException');
    $this->city->set_status('truefalse');
  }
}