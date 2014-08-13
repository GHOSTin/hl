<?php

// /**
//  * @property data_task task
//  */
// class data_task_Test extends PHPUnit_Framework_TestCase {

//   public function setUp(){
//     $this->task = new data_task();
//   }

//   public function test_set_id_1(){
//     $this->task->set_id(10);
//     $this->assertEquals(10, $this->task->get_id());
//   }

//   public function test_set_title_1(){
//     $this->task->set_title('Заголовок');
//     $this->assertEquals('Заголовок', $this->task->get_title());
//   }

//   public function test_set_description_1(){
//     $this->task->set_description('Description');
//     $this->assertEquals('Description', $this->task->get_description());
//   }

//   public function test_set_time_open_1(){
//     $this->task->set_time_open(10);
//     $this->assertEquals(10, $this->task->get_time_open());
//   }

//   public function test_set_time_target_1(){
//     $this->task->set_time_target(11);
//     $this->assertEquals(11, $this->task->get_time_target());
//   }

//   public function test_set_time_close_1(){
//     $this->task->set_time_close(12);
//     $this->assertEquals(12, $this->task->get_time_close());
//   }

//   public function test_set_rating_1(){
//     $this->task->set_rating(4);
//     $this->assertEquals(4, $this->task->get_rating());
//   }

//   public function test_set_reason_1(){
//     $this->task->set_reason('Reason');
//     $this->assertEquals('Reason', $this->task->get_reason());
//   }

//   public function test_set_status_1(){
//     $this->task->set_status('close');
//     $this->assertEquals('close', $this->task->get_status());
//   }

//   public function test_set_creator_1(){
//     $creator = new data_user();
//     $this->task->set_creator($creator);
//     $this->assertSame($creator, $this->task->get_creator());
//     $this->assertInstanceOf('data_user', $this->task->get_creator());
//   }

//   public function test_set_performers_1(){
//     $performer = new data_user();
//     $this->task->set_performers([$performer, $performer, $performer]);
//     $this->assertCount(3, $this->task->get_performers());
//     $this->assertContainsOnlyInstancesOf('data_user', $this->task->get_performers());
//   }

// }
//