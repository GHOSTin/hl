<?php

use domain\meterage;
use domain\number;

class domain_meterage_Test extends PHPUnit_Framework_TestCase{

  public function test_set_number_1(){
    $number = new number();
    $meterage = meterage::new_instance($number, 1396893600, 'Горячая вода', 1, 100, 50);
    $this->assertEquals(1396332000, $meterage->get_time());
    $this->assertEquals('Горячая вода', $meterage->get_service());
    $this->assertEquals(100, $meterage->get_first());
    $this->assertEquals(0, $meterage->get_second());
    $meterage = meterage::new_instance($number, 1396893600, 'Горячая вода', 2, 100, 50);
    $this->assertEquals(50, $meterage->get_second());
  }
}