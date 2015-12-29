<?php namespace tests\domain;

use domain\metrics;
use PHPUnit_Framework_TestCase;

class metrics_Test extends PHPUnit_Framework_TestCase {

  public function setUp(){
    $this->metric = new metrics();
  }

  public function test_set_id_1(){
    $this->metric->set_id('bef03c09ee267d6ae1df235dc3595f6953cbe24e');
    $this->assertEquals('bef03c09ee267d6ae1df235dc3595f6953cbe24e', $this->metric->get_id());
  }

  public function test_set_address_1(){
    $this->metric->set_address("Вайнера 75");
    $this->assertEquals("Вайнера 75", $this->metric->get_address());
  }

  public function test_set_metrics_1(){
    $this->metric->set_metrics("ХВС: 12");
    $this->assertEquals("ХВС: 12", $this->metric->get_metrics());
  }

  public function test_set_status(){
    $this->metric->set_status('archive');
    $this->assertEquals('archive', $this->metric->get_status());
  }

  public function test_set_time(){
    $this->metric->set_time(1397562800);
    $this->assertEquals(1397562800, $this->metric->get_time());
  }
}