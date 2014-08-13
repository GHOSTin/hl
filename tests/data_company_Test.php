<?php

class data_company_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->company = new data_company();
  }

  public function test_set_id_1(){
    $this->company->set_id(125);
    $this->assertEquals(125, $this->company->get_id());
  }

  public function test_set_id_2(){
    $this->setExpectedException('DomainException');
    $this->company->set_id(0);
  }

  public function test_set_id_3(){
    $this->setExpectedException('DomainException');
    $this->company->set_id(256);
  }

  public function test_set_name_1(){
    $this->company->set_name('Наш город');
    $this->assertEquals('Наш город', $this->company->get_name());
  }

  public function test_set_name_2(){
    $this->setExpectedException('DomainException');
    $this->company->set_name('Л');
  }
}