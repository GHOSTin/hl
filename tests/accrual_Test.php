<?php

use domain\accrual;
use domain\number;

class accrual_Test extends PHPUnit_Framework_TestCase{

  public function test_instance(){
    $number = new number();
    $accrual = accrual::new_instance($number, 1396332000, 'Утилизация', 'кг',
                                      127.52, 127.53, 127.54, 127.55,
                                      127.56, 127.57, 127.58, 127.59,
                                      127.51
                                    );
    $this->assertSame($number, $accrual->get_number());
    $this->assertEquals(1396332000, $accrual->get_time());
    $this->assertEquals('Утилизация', $accrual->get_service());
    $this->assertEquals('кг', $accrual->get_unit());
    $this->assertEquals(127.52, $accrual->get_tarif());
    $this->assertEquals(127.53, $accrual->get_ind());
    $this->assertEquals(127.54, $accrual->get_odn());
    $this->assertEquals(127.55, $accrual->get_sum_ind());
    $this->assertEquals(127.56, $accrual->get_sum_odn());
    $this->assertEquals(127.57, $accrual->get_sum_total());
    $this->assertEquals(127.58, $accrual->get_facilities());
    $this->assertEquals(127.59, $accrual->get_recalculation());
    $this->assertEquals(127.51, $accrual->get_total());
  }
}