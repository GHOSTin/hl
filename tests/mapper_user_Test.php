<?php

// class mapper_user_Test extends PHPUnit_Framework_TestCase{

//   public function setUp(){
//     $this->pdo = $this->getMock('pdo_mock');
//     $this->stmt = $this->getMock('PDOStatement');
//     $this->user = new data_user();
//   }

//   public function test_mapper_1_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_user($this->pdo))
//       ->find(123);
//   }

//   public function test_mapper_1_2(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_user($this->pdo))
//       ->find_by_login_and_password('login', 'password');
//   }

//   public function test_mapper_1_3(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_user($this->pdo))
//       ->get_insert_id();
//   }

//   public function test_mapper_1_4(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_user($this->pdo))
//       ->get_users();
//   }

//   public function test_mapper_1_5(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_user($this->pdo))
//       ->insert($this->user);
//   }

//   public function test_mapper_1_6(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_user($this->pdo))
//       ->update($this->user);
//   }

//   public function test_mapper_1_7(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_user($this->pdo))
//       ->find_user_by_login('nekrasov');
//   }
// }