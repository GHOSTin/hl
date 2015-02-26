<?php

use domain\event;
use domain\number;
use domain\number2event;

class number2event_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->n2e = new number2event();
  }

  public function test_set_number(){
    $number = new number();
    $this->n2e->set_number($number);
    $this->assertEquals($number, $this->n2e->get_number());
  }

  public function test_set_event(){
    $event = new event();
    $this->n2e->set_event($event);
    $this->assertEquals($event, $this->n2e->get_event());
  }

  public function test_set_time(){
    $this->n2e->set_time(1397562800);
    $this->assertEquals(1397562800, $this->n2e->get_time());
  }

  public function test_get_name(){
    $event = new event();
    $event->set_name('Привет');
    $this->n2e->set_event($event);
    $this->assertEquals('Привет', $this->n2e->get_name());
  }
}