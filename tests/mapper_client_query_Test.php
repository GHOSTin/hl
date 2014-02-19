<?php

class mapper_client_query_Test extends PHPUnit_Framework_TestCase{

  public static function setUpBeforeClass(){
    $pimple = di::get_instance();
    $pimple['factory_client_query'] = function($p){
      return new factory_client_query();
    };
  }

  public function setUp(){
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->company = new data_company();
    $this->number = new data_number();
    $this->user_data = ['time' => 123, 'text' => 'Порвало трубу',
      'status' => 'accepted', 'number_id' => 123, 'company_id' => 2];
  }

  public function test_mapper_1_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_client_query($this->pdo))
      ->find($this->company, $this->number, 123);
  }

  public function test_mapper_1_2(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_client_query($this->pdo))->find_all_new($this->company);
  }

  public function test_mapper_1_3(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_client_query($this->pdo))->update(new data_client_query());
  }

  public function test_mapper_2_1(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(0));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->assertNull((new mapper_client_query($this->pdo))
      ->find($this->company, $this->number, 123));
  }

  public function test_mapper_3_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(2));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_client_query($this->pdo))
      ->find($this->company, $this->number, 123);
  }

  public function test_mapper_4_1(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(1));
    $this->stmt->expects($this->once())
      ->method('fetch')
      ->will($this->returnValue($this->user_data));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->assertInstanceOf('data_client_query',
      (new mapper_client_query($this->pdo))
      ->find($this->company, $this->number, 123));
  }

  public function test_mapper_4_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
      ->method('fetch')
      ->will($this->onConsecutiveCalls($this->user_data, 
        $this->user_data, false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->assertEquals(2, 
      count((new mapper_client_query($this->pdo))
      ->find_all_new($this->company, $this->number, 123)));
  }
}