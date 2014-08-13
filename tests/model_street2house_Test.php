<?php

// class model_street2house_Test extends PHPUnit_Framework_TestCase{

//   public function setUp(){
//     $this->pimple = new \Pimple\Container();
//     $this->street = new data_street();
//     $this->house = new data_house();
//   }

//   public function test_create_house_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->pimple['mapper_street2house'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_street2house')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find_by_number')
//         ->with($this->street, '57А')
//         ->will($this->returnValue($this->house));
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     (new model_street2house($this->street))->create_house('57А');
//   }

//   public function test_create_house_2(){
//     $this->pimple['mapper_street2house'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_street2house')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find_by_number')
//         ->with($this->street, '57А')
//         ->will($this->returnValue(null));
//       $mapper->expects($this->once())
//         ->method('get_insert_id')
//         ->will($this->returnValue(157));
//       $mapper->expects($this->once())
//         ->method('insert')
//         ->with($this->street, $this->house)
//         ->will($this->returnValue($this->house));
//       return $mapper;
//     };
//     $this->pimple['factory_house'] = function($p){
//       $mapper = $this->getMock('factory_house');
//       $mapper->expects($this->once())
//         ->method('build')
//         ->with(['id' => 157, 'number' => '57А', 'street' => $this->street])
//         ->will($this->returnValue($this->house));
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     $res = (new model_street2house($this->street))->create_house('57А');
//     $this->assertSame($this->house, $res);
//   }

//   public function test_get_house_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->pimple['mapper_street2house'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_street2house')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find')
//         ->with($this->street, 5);
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     (new model_street2house($this->street))->get_house(5);
//   }

//   public function test_get_house_2(){
//     $this->pimple['mapper_street2house'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_street2house')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find')
//         ->with($this->street, 5)
//         ->will($this->returnValue($this->house));
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     $res = (new model_street2house($this->street))->get_house(5);
//     $this->assertSame($this->house, $res);
//   }

//   public function test_get_house_by_number_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->pimple['mapper_street2house'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_street2house')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find_by_number')
//         ->with($this->street, 5);
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     (new model_street2house($this->street))->get_house_by_number(5);
//   }

//   public function test_get_house_by_number_2(){
//     $this->pimple['mapper_street2house'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_street2house')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('find_by_number')
//         ->with($this->street, 5)
//         ->will($this->returnValue($this->house));
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     $res = (new model_street2house($this->street))->get_house_by_number(5);
//     $this->assertSame($this->house, $res);
//   }

//   public function test_init_houses_1(){
//     $this->pimple['mapper_street2house'] = function($p){
//       $mapper = $this->getMockBuilder('mapper_street2house')
//         ->disableOriginalConstructor()
//         ->getMock();
//       $mapper->expects($this->once())
//         ->method('init_houses')
//         ->with($this->street);
//       return $mapper;
//     };
//     di::set_instance($this->pimple);
//     (new model_street2house($this->street))->init_houses($this->street);
//   }
// }