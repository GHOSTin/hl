<?php namespace tests\domain;

use domain\event;
use domain\number;
use domain\number2event;
use PHPUnit_Framework_TestCase;

class number2event_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->number = new number();
    $this->event = event::new_instance('Уборка территории');
    $this->n2e = new number2event($this->number, $this->event, '21.12.1984', 'Привет');
  }

  public function test_1(){
    $this->assertEquals('Уборка территории', $this->n2e->get_name());
    $this->assertEquals('Привет', $this->n2e->get_description());
    $this->assertEquals(strtotime('12:00 21.12.1984'), $this->n2e->get_time());
  }
}
