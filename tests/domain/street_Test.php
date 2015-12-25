<?php namespace tests\domain;

use domain\house;
use domain\street;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

class street_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->street = new street();
  }

  public function test_new_instance(){
    $street = street::new_instance('Ватутина');
    $this->assertInstanceOf('domain\street', $street);
    $this->assertEquals('Ватутина', $street->get_name());
    $this->assertEquals('true', $street->get_status());
  }

  public function test_house_1(){
    $this->setExpectedException('DomainException');
    $house = new house();
    $this->street->add_house($house);
    $this->street->add_house($house);
  }

  public function test_house_2(){
    $house = new house();
    $this->street->add_house($house);
    $this->assertSame($house, $this->street->get_houses()[0]);
  }

  public function test_id(){
    $reflection = new ReflectionClass('domain\street');
    $id = $reflection->getProperty('id');
    $id->setAccessible(true);
    $id->setValue($this->street, 400);
    $id->setAccessible(false);
    $this->assertEquals(400, $this->street->get_id());
  }

  public function test_json(){
    $reflection = new ReflectionClass('domain\street');
    $id = $reflection->getProperty('id');
    $id->setAccessible(true);
    $id->setValue($this->street, 400);
    $id->setAccessible(false);
    $this->street->set_name('Ватутина');
    $this->assertEquals(['id' => 400, 'name' => 'Ватутина'], $this->street->JsonSerialize());
  }

  public function test_name(){
    $this->street->set_name('Ватутина ул');
    $this->assertEquals('Ватутина ул', $this->street->get_name());
  }

  public function test_toString(){
    $this->street->set_name('Ватутина ул');
    $this->assertEquals('Ватутина ул', (string) $this->street);
  }
}