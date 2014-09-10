<?php

class data_accrual_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->ac = new data_accrual();
  }

  public function test_set_number_1(){
    $number = new data_number();
    $this->ac->set_number($number);
    $this->assertSame($number, $this->ac->get_number());
  }

  public function test_set_service_1(){
    $this->ac->set_service('Утилизация');
    $this->assertEquals('Утилизация', $this->ac->get_service());
  }

  public function test_set_time_1(){
    $this->ac->set_time(1396893600);
    $this->assertEquals(1396332000, $this->ac->get_time());
  }

  public function test_set_tarif_1(){
    $this->ac->set_tarif(127.52);
    $this->assertEquals(127.52, $this->ac->get_tarif());
  }

  public function test_set_ind_1(){
    $this->ac->set_ind(127.52);
    $this->assertEquals(127.52, $this->ac->get_ind());
  }

  public function test_set_odn_1(){
    $this->ac->set_odn(127.52);
    $this->assertEquals(127.52, $this->ac->get_odn());
  }

  public function test_set_sum_ind_1(){
    $this->ac->set_sum_ind(127.52);
    $this->assertEquals(127.52, $this->ac->get_sum_ind());
  }

  public function test_set_sum_odn_1(){
    $this->ac->set_sum_odn(127.52);
    $this->assertEquals(127.52, $this->ac->get_sum_odn());
  }

  public function test_set_recalculation_1(){
    $this->ac->set_recalculation(127.52);
    $this->assertEquals(127.52, $this->ac->get_recalculation());
  }

  public function test_set_facilities_1(){
    $this->ac->set_facilities(127.52);
    $this->assertEquals(127.52, $this->ac->get_facilities());
  }

  public function test_set_total_1(){
    $this->ac->set_total(127.52);
    $this->assertEquals(127.52, $this->ac->get_total());
  }

  public function test_set_sum_total_1(){
    $this->ac->set_sum_total(127.52);
    $this->assertEquals(127.52, $this->ac->get_sum_total());
  }
}