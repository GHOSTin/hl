<?php namespace tests\domain;

use domain\task;
use domain\user;
use PHPUnit_Framework_TestCase;

 class task_Test extends PHPUnit_Framework_TestCase {

  const task_id = 125;
  const task_title = 'Тема задачи';
  const task_description = 'Описание задачи 123';
  const task_open_time = 1397562800;
  const task_target_time = 1397562801;
  const task_open_status = 'open';

  public function setUp(){
    $this->user = $this->getMock('domain\user');
  }

  public function test_constructor(){
    $task = new task(self::task_id, $this->user, self::task_title, self::task_description, self::task_target_time, self::task_open_time);
    $this->assertEquals(self::task_id, $task->get_id());
    $this->assertEquals(self::task_title, $task->get_title());
    $this->assertEquals(self::task_description, $task->get_description());
    $this->assertEquals(self::task_open_time, $task->get_time_open());
    $this->assertEquals(self::task_target_time, $task->get_time_target());
    $this->assertEquals(self::task_open_status, $task->get_status());
    $this->assertSame($this->user, $task->get_creator());
  }
 }
