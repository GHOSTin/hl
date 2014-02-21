<?php

class mapper_query_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->company = new data_company();
    $this->params = ['time_open_begin' => 123, 'time_open_end' => 456];
    $this->query = new data_query();
    $this->query->set_id(1);
    $this->query->set_status('open');
    $this->query->set_payment_status('paid');
    $this->query->set_warning_status('normal');
    $this->query->set_time_open(123123);
    $this->query->set_time_work(456456);
    $this->query->set_initiator('number');
    $this->query->set_number(2145);
    $this->query->set_house(new data_house());
    $this->query->add_work_type(new data_query_work_type());
  }

  public function test_mapper_1_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_query($this->pdo, $this->company))
      ->find(123);
  }

  public function test_mapper_1_2(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_query($this->pdo, $this->company))
      ->get_queries_by_number(5633);
  }

  public function test_mapper_1_3(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_query($this->pdo, $this->company))
      ->get_insert_id();
  }

  public function test_mapper_1_4(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_query($this->pdo, $this->company))
      ->get_insert_query_number(time());
  }

  public function test_mapper_1_5(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_query($this->pdo, $this->company))
      ->get_queries($this->params);
  }

  public function test_mapper_1_6(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_query($this->pdo, $this->company))
      ->update($this->query);
  }
}