<?php

class mapper_query2work_Test extends PHPUnit_Framework_TestCase{

  public static $row = ['id' => 5, 'name' => 'Смена лампочки',
    'time_open' => 12345678, 'time_close' => 'Валерьевич', 'value' => 2];

  public function setUp(){
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->company = $this->getMock('data_company');
    $this->company->expects($this->once())
      ->method('get_id');
    $this->query = $this->getMock('data_query');
    $this->query->expects($this->once())
      ->method('get_id');
    $this->query2work = $this->getMockBuilder('data_query2work')
      ->disableOriginalConstructor()
      ->getMock();
    $pimple = new \Pimple\Container();
    $pimple['factory_work'] = function($p){
      return new factory_work();
    };
    di::set_instance($pimple);
  }

  public function test_insert_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $work = $this->getMockBuilder('data_query2work')
      ->disableOriginalConstructor()
      ->getMock();
    $work->expects($this->once())
      ->method('get_time_open');
    $work->expects($this->once())
      ->method('get_time_close');
    (new mapper_query2work($this->pdo))
      ->insert($this->company, $this->query, $work);
  }

  public function test_insert_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $work = $this->getMockBuilder('data_query2work')
      ->disableOriginalConstructor()
      ->getMock();
    $work->expects($this->once())
      ->method('get_time_open');
    $work->expects($this->once())
      ->method('get_time_close');
    (new mapper_query2work($this->pdo))
      ->insert($this->company, $this->query, $work);
  }

  public function test_delete_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $work = $this->getMockBuilder('data_query2work')
      ->disableOriginalConstructor()
      ->getMock();
    (new mapper_query2work($this->pdo))
      ->delete($this->company, $this->query, $work);
  }

  public function test_delete_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $work = $this->getMockBuilder('data_query2work')
      ->disableOriginalConstructor()
      ->getMock();
    (new mapper_query2work($this->pdo))
      ->delete($this->company, $this->query, $work);
  }

  public function test_get_works_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_query2work($this->pdo))
      ->get_works($this->company, $this->query);
  }

  public function test_get_works_2(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
      ->method('fetch')
      ->will($this->onConsecutiveCalls(self::$row, self::$row, false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $res = (new mapper_query2work($this->pdo))
      ->get_works($this->company, $this->query);
    $this->assertCount(2, $res);
  }

  public function test_init_works(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
      ->method('fetch')
      ->will($this->onConsecutiveCalls(self::$row, self::$row, false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->query->expects($this->exactly(2))
      ->method('add_work');
    $res = (new mapper_query2work($this->pdo))
      ->init_works($this->company, $this->query);
  }

  public function test_update_works(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('fetch')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $query = $this->getMock('data_query');
    $this->query->expects($this->once())
      ->method('get_works')
      ->will($this->returnValue([]));
    $res = (new mapper_query2work($this->pdo))
      ->update_works($this->company, $this->query);
  }
}