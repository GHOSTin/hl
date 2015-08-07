<?php

use domain\house;
use domain\department;
use domain\street;
use domain\number;
use domain\flat;

class house_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->house = new house();
  }

  public function test_id(){
    $reflection = new ReflectionClass('domain\house');
    $id = $reflection->getProperty('id');
    $id->setAccessible(true);
    $id->setValue($this->house, 400);
    $id->setAccessible(false);
    $this->assertEquals(400, $this->house->get_id());
  }

  public function test_JsonSerialize(){
    $reflection = new ReflectionClass('domain\house');
    $id = $reflection->getProperty('id');
    $id->setAccessible(true);
    $id->setValue($this->house, 400);
    $id->setAccessible(false);
    $this->house->set_number('12А');
    $this->assertEquals(['id' => 400, 'number' => '12А'], $this->house->JsonSerialize());
  }

  public function test_get_debtors(){
    $this->assertEquals([], $this->house->get_debtors(1000));
  }

  public function test_new_instance(){
    $street = new street();
    $department = new department();
    $house = house::new_instance($department, $street, '52А');
    $this->assertInstanceOf('domain\house', $house);
    $this->assertEquals('52А', $house->get_number());
    $this->assertSame($street, $house->get_street());
    $this->assertSame($department, $house->get_department());
    $this->assertEquals('true', $house->get_status());
  }

  public function test_toString(){
    $this->house->set_number('125А');
    $this->assertEquals('125А', $this->house);
  }

  public function test_set_number_1(){
    $this->house->set_number('1А');
    $this->assertEquals('1А', $this->house->get_number());
    $this->house->set_number('1/А');
    $this->assertEquals('1/А', $this->house->get_number());
    $this->house->set_number('1/1');
    $this->assertEquals('1/1', $this->house->get_number());
  }

  public function test_set_number_2(){
    $this->setExpectedException('DomainException');
    $this->house->set_number('Первый');
  }

  public function test_add_number_1(){
    $number = new number();
    $this->house->add_number($number);
    $numbers = $this->house->get_numbers();
    $this->assertCount(1, $numbers);
    $this->assertSame($number, $numbers[0]);
  }

  public function test_add_number_2(){
    $this->setExpectedException('DomainException');
    $number = new number();
    $this->house->add_number($number);
    $this->house->add_number($number);
  }

  public function test_add_flat_1(){
    $flat = new flat();
    $this->house->add_flat($flat);
    $flats = $this->house->get_flats();
    $this->assertCount(1, $flats);
    $this->assertSame($flat, $flats[0]);
  }

  public function test_add_flat_2(){
    $this->setExpectedException('DomainException');
    $flat = new flat();
    $this->house->add_flat($flat);
    $this->house->add_flat($flat);
  }

  public function test_get_queries(){
    $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection',
                            $this->house->get_queries());
  }
}