<?php

// class mapper_session_Test extends PHPUnit_Framework_TestCase{

//   public function setUp(){
//     $this->pimple = new \Pimple\Container();
//     $this->user = new data_user();
//     $this->session = new data_session();
//     $this->session->set_user($this->user);
//     $this->pdo = $this->getMock('pdo_mock');
//     $this->stmt = $this->getMock('PDOStatement');
//     $this->session_row = ['s_time' => 123, 's_ip' => '192.168.0.1',
//       'u_id' => 2, 'u_status' => 'true', 'u_login' => 'NekrasovEV',
//       'u_lastname' => 'Некрасов', 'u_firstname' => 'Евгений',
//       'u_middlename' => 'Валерьевич'];
//   }

//   public function test_find_all_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $mapper = new mapper_session($this->pdo);
//     $mapper->find_all();
//   }

//   public function test_find_all_2(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->exactly(3))
//       ->method('fetch')
//       ->will($this->onConsecutiveCalls($this->session_row,
//       $this->session_row, false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $this->pimple['factory_user'] = function($p){
//       return new factory_user();
//     };
//     $this->pimple['factory_session'] = function($p){
//       return new factory_session();
//     };
//     di::set_instance($this->pimple);
//     $mapper = new mapper_session($this->pdo);
//     $mapper->find_all();
//   }

//   public function test_insert_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $mapper = new mapper_session($this->pdo);
//     $mapper->insert($this->session);
//   }

//   public function test_insert_2(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $mapper = new mapper_session($this->pdo);
//     $mapper->insert($this->session);
//   }
// }