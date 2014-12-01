<?php

use \domain\flat;
use \domain\house;

class flat_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->flat = new flat();
  }

  public function test_set_id_1(){
    $this->flat->set_id(125);
    $this->assertEquals(125, $this->flat->get_id());
  }

  public function test_set_id_2(){
    $this->setExpectedException('DomainException');
    $this->flat->set_id(0);
  }

  public function test_set_id_3(){
    $this->setExpectedException('DomainException');
    $this->flat->set_id(16777216);
  }

  public function test_set_id_4(){
    $this->setExpectedException('DomainException');
    $this->flat->set_id(-125);
  }

  public function test_set_number_1(){
    $this->flat->set_number(125);
    $this->assertEquals(125, $this->flat->get_number());
  }

  public function test_set_number_2(){
    $this->flat->set_number('125.1');
    $this->assertEquals('125.1', $this->flat->get_number());
  }

  public function test_set_number_3(){
    $this->setExpectedException('DomainException');
    $this->flat->set_number('125.1А');
  }

  public function test_set_status_1(){
    $this->flat->set_status('true');
    $this->assertEquals('true', $this->flat->get_status());
    $this->flat->set_status('false');
    $this->assertEquals('false', $this->flat->get_status());
  }

  public function test_set_status_2(){
    $this->setExpectedException('DomainException');
    $this->flat->set_status('truefalse');
  }

  public function test_set_house(){
    $house = new house();
    $this->flat->set_house($house);
    $this->assertSame($house, $this->flat->get_house());
  }
}