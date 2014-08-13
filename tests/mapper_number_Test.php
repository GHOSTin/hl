<?php

// class mapper_number_Test extends PHPUnit_Framework_TestCase{

//   public function setUp(){
//     $this->pdo = $this->getMock('pdo_mock');
//     $this->stmt = $this->getMock('PDOStatement');
//     $this->company = new data_company();
//     $this->number = new data_number();
//     $this->number->set_id(123);
//     $this->number->set_number(123123);
//     $this->number->set_fio('Некрасов Евгений Валерьевич');
//     $this->number_data = ['id' => 123, 'fio' => 'Некрасов Евгений Валерьевич',
//       'number' => 3456, 'status' => false, 'password' => 'sfsdfsdf',
//       'email' => 'nekrasov@mlsco.ru', 'telephone' => null, 'cellphone' => null,
//       'house_id' => 234, 'house_number' => 12, 'house_status' => 'false',
//       'flat_id' => 1,
//       'flat_number' => 19, 'street_id' => 1, 'street_name' => 'Ватутина'];
//     $pimple = new \Pimple\Container();
//     $pimple['factory_flat'] = function($p){
//       return new factory_flat();
//     };
//     $pimple['factory_house'] = function($p){
//       return new factory_house();
//     };
//     $pimple['factory_number'] = function($p){
//       return new factory_number();
//     };
//     $pimple['factory_street'] = function($p){
//       return new factory_street();
//     };
//     di::set_instance($pimple);
//   }

//   public function test_mapper_1_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_number($this->pdo, $this->company))
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
//     (new mapper_number($this->pdo, $this->company))
//       ->find_by_number(123);
//   }

//   public function test_mapper_1_3(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_number($this->pdo, $this->company))
//       ->update($this->number);
//   }

//   public function test_mapper_2_1(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->once())
//       ->method('rowCount')
//       ->will($this->returnValue(0));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $this->assertNull((new mapper_number($this->pdo, $this->company))
//       ->find(123));
//   }

//   public function test_mapper_2_2(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->once())
//       ->method('rowCount')
//       ->will($this->returnValue(0));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $this->assertNull((new mapper_number($this->pdo, $this->company))
//       ->find_by_number(123));
//   }

//   public function test_mapper_3_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->once())
//       ->method('rowCount')
//       ->will($this->returnValue(2));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_number($this->pdo, $this->company))
//       ->find(123);
//   }

//   public function test_mapper_3_2(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->once())
//       ->method('rowCount')
//       ->will($this->returnValue(2));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_number($this->pdo, $this->company))
//       ->find_by_number(123);
//   }

//   public function test_mapper_4_1(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->once())
//       ->method('rowCount')
//       ->will($this->returnValue(1));
//     $this->stmt->expects($this->once())
//       ->method('fetch')
//       ->will($this->returnValue($this->number_data));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $this->assertInstanceOf('data_number',
//       (new mapper_number($this->pdo, $this->company))
//       ->find(123));
//   }

//   public function test_mapper_4_2(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->once())
//       ->method('rowCount')
//       ->will($this->returnValue(1));
//     $this->stmt->expects($this->once())
//       ->method('fetch')
//       ->will($this->returnValue($this->number_data));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $this->assertInstanceOf('data_number',
//       (new mapper_number($this->pdo, $this->company))
//       ->find_by_number(123));
//   }
// }