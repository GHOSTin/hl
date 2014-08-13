<?php

// /**
//  * @property mixed stmt
//  * @property mixed pdo
//  */

// class mapper_user2task_Test extends PHPUnit_Framework_TestCase {
//   protected function setUp(){
//     $this->pdo = $this->getMock('pdo_mock');
//     $this->stmt = $this->getMock('PDOStatement');
//   }

//   public function test_insert_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//         ->method('execute')->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//         ->method('prepare')
//         ->will($this->returnValue($this->stmt));
//     (new mapper_user2task($this->pdo))->insert(20130218000005, 1, 'creator');
//   }

//   public function test_insert_2(){
//     $this->stmt->expects($this->once())
//         ->method('execute')->will($this->returnValue(true));
//     $this->pdo->expects($this->once())
//         ->method('prepare')
//         ->will($this->returnValue($this->stmt));
//     (new mapper_user2task($this->pdo))->insert(20130218000005, 1, 'creator');
//   }

//   public function test_delete_performers_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//         ->method('execute')->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//         ->method('prepare')
//         ->will($this->returnValue($this->stmt));
//     (new mapper_user2task($this->pdo))->delete_performers(20130218000005);
//   }

//   public function test_delete_performers_2(){
//     $this->stmt->expects($this->once())
//         ->method('execute')->will($this->returnValue(true));
//     $this->pdo->expects($this->once())
//         ->method('prepare')
//         ->will($this->returnValue($this->stmt));
//     (new mapper_user2task($this->pdo))->delete_performers(20130218000005);
//   }

// }
//