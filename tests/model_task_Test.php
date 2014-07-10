<?php

/**
 * @property Pimple pimple
 * @property mixed pdo
 * @property mixed stmt
 * @property data_task task
 * @property data_user user
 */
class model_task_Test extends PHPUnit_Framework_TestCase {

  protected function setUp(){
    $this->pimple = new Pimple();
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->task = new data_task();
    $this->user = new data_user();
    $this->pimple['user'] = $this->user;
    $this->pimple['factory_task'] = function($p){
      return new factory_task();
    };
  }

  public function test_add_task(){
    $this->pdo->expects($this->once())
      ->method('beginTransaction');
    $this->pdo->expects($this->once())
      ->method('commit');
    $this->pimple['pdo'] = $this->pdo;
    $this->pimple['mapper_task'] = function($p){
      $mapper = $this->getMockBuilder('mapper_task')
          ->disableOriginalConstructor()
          ->getMock();
      $mapper->expects($this->once())
        ->method('insert');
      $mapper->expects($this->once())
        ->method('get_insert_id')
        ->will($this->returnValue(2014071000001));
      return $mapper;
    };
    $this->pimple['model_user2task'] = function($p){
      $model = $this->getMock('model_user2task');
      $model->expects($this->once())
        ->method('add_users');
      return $model;
    };
    $this->pimple['model_user'] = function($p){
      $model = $this->getMock('model_user');
      $model->expects($this->exactly(2))
        ->method('get_user')
        ->will($this->returnValue($this->user));
      return $model;
    };
    di::set_instance($this->pimple);
    /** @var data_task $task */
    $task = (new model_task())->add_task('Title', 'Description', 1397562800, ['1', '2']);
    $this->assertInstanceOf('data_task', $task);
    $this->assertEquals('2014071000001', $task->get_id());
    $this->assertEquals('Title', $task->get_title());
    $this->assertEquals('Description', $task->get_description());
    $this->assertEquals('1397562800', $task->get_time_target());
    $this->assertContainsOnlyInstancesOf('data_user', $task->get_performers());
  }

  public function test_save_task(){
    $this->pdo->expects($this->once())
      ->method('beginTransaction');
    $this->pdo->expects($this->once())
      ->method('commit');
    $this->pimple['pdo'] = $this->pdo;
    $this->pimple['mapper_task'] = function($p){
      $mapper = $this->getMockBuilder('mapper_task')
          ->disableOriginalConstructor()
          ->getMock();
      $mapper->expects($this->once())
        ->method('update');
      $this->task->set_id(2014071000001);
      $mapper->expects($this->once())
        ->method('find')
        ->will($this->returnValue($this->task));
      return $mapper;
    };
    $this->pimple['model_user2task'] = function($p){
      $model = $this->getMock('model_user2task');
      $model->expects($this->once())
        ->method('update');
      return $model;
    };
    $this->pimple['model_user'] = function($p){
      $model = $this->getMock('model_user');
      $model->expects($this->exactly(3))
        ->method('get_user')
        ->will($this->returnValue($this->user));
      return $model;
    };
    di::set_instance($this->pimple);
    /** @var data_task $task */
    $task = (new model_task())->save_task(2014071000001, 'Title', 'Description',
        1397562800, ['1', '2', '3'], ['status'=>'open']);
    $this->assertInstanceOf('data_task', $task);
    $this->assertEquals('2014071000001', $task->get_id());
    $this->assertEquals('Title', $task->get_title());
    $this->assertEquals('Description', $task->get_description());
    $this->assertEquals('1397562800', $task->get_time_target());
    $this->assertContainsOnlyInstancesOf('data_user', $task->get_performers());
  }

  public function test_close_task(){
    $this->pdo->expects($this->once())
        ->method('beginTransaction');
    $this->pdo->expects($this->once())
        ->method('commit');
    $this->pimple['pdo'] = $this->pdo;
    $this->pimple['mapper_task'] = function($p){
      $mapper = $this->getMockBuilder('mapper_task')
          ->disableOriginalConstructor()
          ->getMock();
      $mapper->expects($this->once())
          ->method('update');
      $this->task->set_id(2014071000001);
      $mapper->expects($this->once())
          ->method('find')
          ->will($this->returnValue($this->task));
      return $mapper;
    };
    di::set_instance($this->pimple);
    $result = (new model_task())->close_task(2014071000001, 'Reason', 'item-4', 1397762800);
    $this->assertTrue($result);
  }

  public function test_add_comment(){
    $this->pdo->expects($this->once())
        ->method('beginTransaction');
    $this->pdo->expects($this->once())
        ->method('commit');
    $this->pimple['pdo'] = $this->pdo;
    $this->pimple['mapper_task2comment'] = function($p){
      $mapper = $this->getMockBuilder('mapper_task2comment')
          ->disableOriginalConstructor()
          ->getMock();
      $mapper->expects($this->once())
          ->method('insert');
      return $mapper;
    };
    di::set_instance($this->pimple);
    $result = (new model_task())->add_comment(2014071000001, 'Comment1');
    $this->assertTrue($result);
  }
}
 