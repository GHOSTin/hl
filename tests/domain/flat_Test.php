<?php namespace tests\domain;

use domain\flat;
use domain\house;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

class flat_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->flat = new flat();
  }

  public function test_id(){
    $reflection = new ReflectionClass('domain\flat');
    $id = $reflection->getProperty('id');
    $id->setAccessible(true);
    $id->setValue($this->flat, 400);
    $id->setAccessible(false);
    $this->assertEquals(400, $this->flat->get_id());
  }

  public function test_JsonSerialize(){
    $reflection = new ReflectionClass('domain\flat');
    $id = $reflection->getProperty('id');
    $id->setAccessible(true);
    $id->setValue($this->flat, 400);
    $id->setAccessible(false);
    $this->flat->set_number('12');
    $this->assertEquals(['id' => 400, 'number' => '12'], $this->flat->JsonSerialize());
  }

  public function test_new_instance(){
    $house = new house();
    $flat = flat::new_instance($house, '56');
    $this->assertInstanceOf('domain\flat', $flat);
    $this->assertEquals('56', $flat->get_number());
    $this->assertSame($house, $flat->get_house());
    $this->assertEquals('true', $flat->get_status());
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

  public function test_toString(){
    $this->flat->set_number(13);
    $this->assertEquals('13', $this->flat);
  }

  public function test_numbers(){
    $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->flat->get_numbers());
  }
}