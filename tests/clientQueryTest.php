<?php

// class clientQueryTest extends PHPUnit_Framework_TestCase{

//   public function setUp(){
//     $this->query = new data_client_query();
//   }

//   public function testTime1(){
//     $this->query->set_time(156);
//     $this->assertEquals(156, $this->query->get_time());
//   }

//   public function testTime2(){
//     $this->setExpectedException('Exception');
//     $this->query->set_time();
//   }

//   public function testTime3(){
//     $this->setExpectedException('DomainException');
//     $this->query->set_time(0);
//   }

//   public function testTime4(){
//     $this->setExpectedException('DomainException');
//     $this->query->set_time(2145916801);
//   }

//   public function testTime5(){
//     $this->setExpectedException('DomainException');
//     $this->query->set_time(-1);
//   }

//   public function testStatus1(){
//     $this->query->set_status('new');
//     $this->assertEquals('new', $this->query->get_status());
//   }

//   public function test_status_2(){
//     try{
//       $this->query->set_status('new');
//       $this->query->set_status('accepted');
//       $this->query->set_status('canceled');
//     }catch(exception $e){
//       $this->fail('Не прошел статус');
//     }
//   }

//   public function test_status_5(){
//     $this->setExpectedException('DomainException');
//     $this->query->set_status('opened');
//   }

//   public function test_reason_1(){
//     $this->query->set_reason('Не указано описание');
//     $this->assertEquals('Не указано описание', $this->query->get_reason());
//   }

//   public function test_number_id_1(){
//     $this->query->set_number_id(123);
//     $this->assertEquals(123, $this->query->get_number_id());
//   }

//   public function test_company_id_1(){
//     $this->query->set_company_id(123);
//     $this->assertEquals(123, $this->query->get_company_id());
//   }

//   public function test_query_id_1(){
//     $this->query->set_query_id(123);
//     $this->assertEquals(123, $this->query->get_query_id());
//   }

//   public function test_query_id_2(){
//     $this->setExpectedException('Exception');
//     $this->query->set_query_id();
//   }

//   public function testText1(){
//     $this->query->set_text('Прорвало трубу');
//     $this->assertEquals('Прорвало трубу', $this->query->get_text());
//   }

//   public function testText2(){
//     $this->setExpectedException('Exception');
//     $this->query->set_text();
//   }

//   public function testText3(){
//     $this->setExpectedException('DomainException');
//     $this->query->set_text('Hello world!');
//   }

//   public function testText4(){
//     $this->setExpectedException('DomainException');
//     $this->query->set_text('Буква');
//   }
// }