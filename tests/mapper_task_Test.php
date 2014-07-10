<?php

/**
 * @property mixed pdo
 * @property mixed stmt
 * @property data_user user
 * @property array row
 * @property data_task task
 */
class mapper_task_Test extends PHPUnit_Framework_TestCase {

  protected function setUp(){
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->user = new data_user();
    $this->task = new data_task();
    $pimple = new Pimple();
    $pimple['factory_task'] = function($p){
      return new factory_task();
    };
    $pimple['user'] = function($p){
      return new data_user();
    };
    di::set_instance($pimple);
    $this->row = ['id' => 101,
        'title' => 'Title', 'time_open' => 1396893600,'time_close' => null, 'time_target' => 1397562800,
        'description' => 'Утилизация', 'rating' => 2, 'reason' => 'Reason', 'status' => 'close',
        'users_id' => '5,2,3,1', 'users_type' => 'creator, performer, performer, performer'
    ];
  }

  public function test_find_active_task_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_task($this->pdo))
      ->find_active_tasks();
  }

  public function test_find_active_task_2(){
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
    $pimple = di::get_instance();
    $pimple['mapper_task2comment'] = function($p){
      $mapper = $this->getMockBuilder('mapper_task2comment')
          ->disableOriginalConstructor()
          ->getMock();
      $mapper->expects($this->exactly(2))
          ->method('find_all')
          ->will($this->returnValue(array()));
      return $mapper;
    };
    $pimple['model_user'] = function($p){
      $user = $this->getMock('model_user');
      $user->expects($this->exactly(8))
        ->method('get_user')
        ->will($this->returnValue($this->user));
      return $user;
    };
    di::set_instance($pimple);
    $tasks = (new mapper_task($this->pdo))
      ->find_active_tasks();
    $this->assertCount(2, $tasks);
    $this->assertContainsOnlyInstancesOf('data_task', $tasks);
  }

  public function test_find_finished_task_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_task($this->pdo))
      ->find_finished_tasks();
  }

  public function test_find_finished_task_2(){
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
    $pimple = di::get_instance();
    $pimple['mapper_task2comment'] = function($p){
      $mapper = $this->getMockBuilder('mapper_task2comment')
          ->disableOriginalConstructor()
          ->getMock();
      $mapper->expects($this->exactly(2))
          ->method('find_all')
          ->will($this->returnValue(array()));
      return $mapper;
    };
    $pimple['model_user'] = function($p){
      $user = $this->getMock('model_user');
      $user->expects($this->exactly(8))
        ->method('get_user')
        ->will($this->returnValue($this->user));
      return $user;
    };
    di::set_instance($pimple);
    $tasks = (new mapper_task($this->pdo))
      ->find_finished_tasks();
    $this->assertCount(2, $tasks);
    $this->assertContainsOnlyInstancesOf('data_task', $tasks);
  }

  public function test_get_insert_id_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(false));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_task($this->pdo))->get_insert_id();
  }

  public function test_get_insert_id_2(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(true));
    $this->stmt->expects($this->once())
        ->method('rowCount')->will($this->returnValue(0));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_task($this->pdo))->get_insert_id();
  }

  public function test_get_insert_id_3(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(true));
    $this->stmt->expects($this->once())
        ->method('rowCount')->will($this->returnValue(2));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_task($this->pdo))->get_insert_id();
  }

  public function test_get_insert_id_4(){
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(true));
    $this->stmt->expects($this->once())
        ->method('rowCount')->will($this->returnValue(1));
    $this->stmt->expects($this->once())
        ->method('fetch')->will($this->returnValue(['id' => null]));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    $mapper = new mapper_task($this->pdo);
    $prefix = date('Ymd');
    $this->assertEquals($prefix.'000001', $mapper->get_insert_id());
  }

  public function test_get_insert_id_5(){
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(true));
    $this->stmt->expects($this->once())
        ->method('rowCount')->will($this->returnValue(1));
    $this->stmt->expects($this->once())
        ->method('fetch')->will($this->returnValue(['id' => 20130218000005]));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    $mapper = new mapper_task($this->pdo);
    $this->assertEquals(20130218000006, $mapper->get_insert_id());
  }

  public function test_find_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(false));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_task($this->pdo))->find(123);
  }

  public function test_find_2(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(true));
    $this->stmt->expects($this->once())
        ->method('rowCount')->will($this->returnValue(0));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    $mapper = new mapper_task($this->pdo);
    $this->assertNull($mapper->find(123));
  }

  public function test_find_3(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(true));
    $this->stmt->expects($this->once())
        ->method('rowCount')->will($this->returnValue(2));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_task($this->pdo))->find(123);
  }

  public function test_find_4(){
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(true));
    $this->stmt->expects($this->once())
        ->method('rowCount')->will($this->returnValue(1));
    $this->stmt->expects($this->once())
        ->method('fetch')->will($this->returnValue($this->row));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    $pimple = di::get_instance();
    $pimple['mapper_task2comment'] = function($p){
      $mapper = $this->getMockBuilder('mapper_task2comment')
          ->disableOriginalConstructor()
          ->getMock();
      $mapper->expects($this->once())
          ->method('find_all')
          ->will($this->returnValue(array()));
      return $mapper;
    };
    $pimple['model_user'] = function($p){
      $user = $this->getMock('model_user');
      $user->expects($this->exactly(4))
          ->method('get_user')
          ->will($this->returnValue($this->user));
      return $user;
    };
    di::set_instance($pimple);
    $task = (new mapper_task($this->pdo))->find(123);
    $this->assertInstanceOf('data_task', $task);
  }

  public function test_insert_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(false));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_task($this->pdo))->insert($this->task);
  }

  public function test_insert_2(){
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(true));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_task($this->pdo))->insert($this->task);
  }

  public function test_update_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(false));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_task($this->pdo))->update($this->task);
  }

  public function test_update_2(){
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(true));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_task($this->pdo))->update($this->task);
  }

}
 