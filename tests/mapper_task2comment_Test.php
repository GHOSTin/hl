<?php

/**
 * @property mixed pdo
 * @property mixed stmt
 * @property data_task task
 * @property Pimple pimple
 * @property data_user user
 * @property array row
 */
class mapper_task2comment_Test extends PHPUnit_Framework_TestCase {
  protected function setUp(){
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->task = new data_task();
    $this->user = new data_user();
    $this->pimple = new \Pimple\Container();
    $this->pimple['user'] = $this->user;
    $this->pimple['factory_task2comment'] = function($p){
      return new factory_task2comment();
    };
    di::set_instance($this->pimple);
    $this->row = ['message' => 'Comment1', 'time' => 1397562800, 'user_id' => $this->user->get_id()];
  }

  public function test_find_all_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(false));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_task2comment($this->pdo))->find_all($this->task->get_id());
  }

  public function test_find_all_2(){
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
    $this->pimple['model_user'] = function($p){
      $model = $this->getMock('model_user');
      $model->expects($this->exactly(2))
          ->method('get_user')
          ->will($this->returnValue($this->user));
      return $model;
    };
    $comments = (new mapper_task2comment($this->pdo))->find_all($this->task->get_id());
    $this->assertContainsOnlyInstancesOf('data_task2comment', $comments);
    $this->assertCount(2, $comments);
  }

  public function test_insert_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(false));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_task2comment($this->pdo))->insert($this->task->get_id(), 'Comment1');
  }

  public function test_insert_2(){
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(true));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_task2comment($this->pdo))->insert($this->task->get_id(), 'Comment1');
  }

}
