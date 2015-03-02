<?php

use domain\event;

class event_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->event = new event();
  }

  public function test_set_id_1(){
    $this->event->set_id(125);
    $this->assertEquals(125, $this->event->get_id());
  }

  public function test_set_id_2(){
    $this->setExpectedException('DomainException');
    $this->event->set_id(0);
  }

  public function test_set_id_3(){
    $this->setExpectedException('DomainException');
    $this->event->set_id(65535 + 1);
  }

  public function test_set_id_4(){
    $this->setExpectedException('DomainException');
    $this->event->set_id(-125);
  }

  public function test_set_name_1(){
    $this->event->set_name('Перекрытие стояка-задвижки');
    $this->assertEquals('Перекрытие стояка-задвижки', $this->event->get_name());
  }

  public function test_set_name_2(){
    $this->setExpectedException('DomainException');
    $this->event->set_name('hello');
  }

  public function test_set_name_3(){
    $this->setExpectedException('DomainException');
    $this->event->set_name('');
  }

  public function test_set_name_4(){
    $this->setExpectedException('DomainException');
    $this->event->set_name('123123');
  }
}