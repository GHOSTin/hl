<?php

class mapper_error_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->user = new data_user();
    $this->error = new data_error();
    $this->error->set_user($this->user);
    $pimple = new \Pimple\Container();
    $pimple['factory_error'] = function($p){
      return new factory_error();
    };
    di::set_instance($pimple);
    $this->error_row = ['id' => 2, 'firstname' => 'Евгений',
      'lastname' => 'Некрасов', 'midlename' => 'Валерьевич',
      'text' => 'Произошла ошибка', 'time' => 12312312];
  }

  public function test_1_delete(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_error($this->pdo))
      ->delete($this->error);
  }

  public function test_1_find(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_error($this->pdo))
      ->find(1231213, 123);
  }

  public function test_1_find_all(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_error($this->pdo))
      ->find_all();
  }

  public function test_1_insert(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_error($this->pdo))
      ->insert($this->error);
  }

  public function test_2_find(){
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
    (new mapper_error($this->pdo))
      ->find(1231213, 123);
  }

  public function test_3_find(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(0));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $error = (new mapper_error($this->pdo))
      ->find(1231213, 123);
    $this->assertNull($error);
  }

  public function test_4_find(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(1));
    $this->stmt->expects($this->once())
      ->method('fetch')
      ->will($this->returnValue($this->error_row));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $error = (new mapper_error($this->pdo))
      ->find(1231213, 123);
    $this->assertInstanceOf('data_error', $error);
  }

  public function test_4_find_all(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
      ->method('fetch')
      ->will($this->onConsecutiveCalls($this->error_row,
      $this->error_row, false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $errors = (new mapper_error($this->pdo))
      ->find_all();
    $this->assertEquals(2, count($errors));
    $this->assertContainsOnlyInstancesOf('data_error', $errors);
  }
}