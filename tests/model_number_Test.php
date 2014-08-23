<?php

// class model_number_Test extends PHPUnit_Framework_TestCase{

//   public function setUp(){
//     $this->company = new data_company();
//     $this->company->set_id(1);
//     $this->number = new data_number();
//     $this->pimple = new \Pimple\Container();
//   }

//   public function test_model_1_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->pimple['mapper_number'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_number')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find')
//         ->will($this->returnValue(null));
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     (new model_number($this->company))->get_number(123);
//   }

//   public function test_model_2_1(){
//     $this->pimple['mapper_number'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_number')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find')
//         ->will($this->returnValue($this->number));
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     $number = (new model_number($this->company))->get_number(123);
//     $this->assertSame($this->number, $number);
//   }

//   public function test_model_2_2(){
//     $this->pimple['mapper_number'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_number')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find')
//         ->will($this->returnValue($this->number));
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     $number = (new model_number($this->company))->update_number(123, 4567);
//     $this->assertSame($this->number, $number);
//   }

//   public function test_model_2_3(){
//     $this->pimple['mapper_number'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_number')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find')
//         ->will($this->returnValue($this->number));
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     $number = (new model_number($this->company))
//       ->update_password(123, 'password');
//     $this->assertSame($this->number, $number);
//   }

//   public function test_model_2_4(){
//     $this->pimple['mapper_number'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_number')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find')
//         ->will($this->returnValue($this->number));
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     $number = (new model_number($this->company))
//       ->update_number_fio(123, 'Некрасов Евгений Валерьевич');
//     $this->assertSame($this->number, $number);
//   }

//   public function test_model_2_5(){
//     $this->pimple['mapper_number'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_number')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find')
//         ->will($this->returnValue($this->number));
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     $number = (new model_number($this->company))
//       ->update_number_cellphone(123, 89222944742);
//     $this->assertSame($this->number, $number);
//   }

//   public function test_model_2_6(){
//     $this->pimple['mapper_number'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_number')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find')
//         ->will($this->returnValue($this->number));
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     $number = (new model_number($this->company))
//       ->update_number_email(123, 'nekrasov@mlsco.ru');
//     $this->assertSame($this->number, $number);
//   }

//   public function test_model_2_7(){
//     $this->pimple['mapper_number'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_number')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find')
//         ->will($this->returnValue($this->number));
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     $number = (new model_number($this->company))
//       ->update_number_telephone(123, 83439647957);
//     $this->assertSame($this->number, $number);
//   }
// }