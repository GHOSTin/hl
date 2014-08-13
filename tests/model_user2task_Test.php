<?php

// /**
//  * @property mixed pdo
//  * @property Pimple pimple
//  * @property data_task task
//  * @property data_user user
//  */
// class model_user2task_Test extends PHPUnit_Framework_TestCase {

//   protected function setUp(){
//     $this->pdo = $this->getMock('pdo_mock');
//     $this->pimple = new \Pimple\Container();
//     $this->task = new data_task();
//     $this->user = new data_user();
//   }

//   public function test_add_users(){
//     $this->pimple['mapper_user2task'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_user2task')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->exactly(3))
//         ->method('insert');
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     $this->task->set_creator($this->user);
//     $this->task->set_performers([$this->user, $this->user]);
//     (new model_user2task())->add_users($this->task);
//   }

//   public function test_update(){
//     $this->pimple['mapper_user2task'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_user2task')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->exactly(2))
//         ->method('insert');
//       $mapper->expects($this->once())
//         ->method('delete_performers');
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     $this->task->set_performers([$this->user, $this->user]);
//     (new model_user2task())->update($this->task);
//   }

// }
