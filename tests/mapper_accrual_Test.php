<?php

class mapper_accrual_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $pimple = new Pimple();
    $pimple['factory_accrual'] = function($p){
      return new factory_accrual();
    };
    di::set_instance($pimple);
    $this->row = ['company' => new data_company(),
      'number' => new data_number(), 'time' => 1396893600,
      'service' => 'Утилизация', 'tarif' => 127.56, 'ind' => 127.56,
      'odn' => 127.56, 'sum_ind' => 127.56, 'sum_odn' => 127.56,
      'recalculation' => 127.56, 'facilities' => 127.56, 'total' => 127.56];
  }

  public function test_insert_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $company = $this->getMock('data_company');
    $company->expects($this->once())
      ->method('get_id');
    $number = $this->getMock('data_number');
    $number->expects($this->once())
      ->method('get_id');
    (new mapper_accrual($this->pdo))
      ->find_all($company, $number);
  }

  public function test_insert_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
      ->method('fetch')
      ->will($this->onConsecutiveCalls($this->row,
      $this->row, false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $company = $this->getMock('data_company');
    $company->expects($this->once())
      ->method('get_id');
    $number = $this->getMock('data_number');
    $number->expects($this->once())
      ->method('get_id');
    $accruals = (new mapper_accrual($this->pdo))
      ->find_all($company, $number);
    $this->assertCount(2, $accruals);
  }
}