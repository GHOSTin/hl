<?php

use domain\query_type;

class query_type_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->query_type = new query_type();
  }


  public function test_get_id(){
    $reflection = new ReflectionClass('domain\query_type');
    $id = $reflection->getProperty('id');
    $id->setAccessible(true);
    $id->setValue($this->query_type, 125);
    $this->assertEquals(125, $this->query_type->get_id());
  }

  public function test_new_instance(){
    $query_type = query_type::new_instance('Привет');
    $this->assertEquals('Привет', $query_type->get_name());
  }

  public function test_set_name_1(){
    $this->query_type->set_name('Привет -Я');
    $this->assertEquals('Привет -Я', $this->query_type->get_name());
  }

  public function test_set_name_2(){
    $this->setExpectedException('DomainException');
    $this->query_type->set_name('Kachestvennie usligu');
  }

  public function test_set_name_3(){
    $this->setExpectedException('DomainException');
    $this->query_type->set_name(str_repeat('ПРиветия', 32));
  }

  public function test_set_name_4(){
    $this->setExpectedException('DomainException');
    $this->query_type->set_name('');
  }
}