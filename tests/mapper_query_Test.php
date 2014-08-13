<?php

// class mapper_query_Test extends PHPUnit_Framework_TestCase{

//   public function setUp(){
//     $this->pdo = $this->getMock('pdo_mock');
//     $this->stmt = $this->getMock('PDOStatement');
//     $this->company = new data_company();
//     $this->house = new data_house();
//     $this->house->set_id(1);
//     $this->department = new data_department();
//     $this->department->set_id(1);
//     $this->type = new data_query_work_type();
//     $this->type->set_id(1);
//     $this->params = ['time_open_begin' => 123, 'time_open_end' => 456];
//     $this->query = new data_query();
//     $this->query->set_id(1);
//     $this->query->set_status('open');
//     $this->query->set_payment_status('paid');
//     $this->query->set_warning_status('normal');
//     $this->query->set_time_open(123123);
//     $this->query->set_time_work(456456);
//     $this->query->set_initiator('number');
//     $this->query->set_number(2145);
//     $this->query->set_house($this->house);
//     $this->query->set_department($this->department);
//     $this->query->add_work_type($this->type);

//     $this->query_row = ['worktype_id' => 2, 'work_type_name' => 'Электричество',
//       'department_id' => 1, 'department_name' => 'Первый', 'street_id' => 2,
//       'street_name' => 'Ватутина', 'house_id' => 1, 'house_number' => 52,
//       'house_status' => 'false',
//       'street' => new data_street(), 'id' => 1059, 'status' => 'open',
//       'initiator' => 'house', 'payment_status' => 'unpaid',
//       'warning_status' => 'normal', 'close_reason' => 'Закрыта',
//       'time_open' => 123123, 'time_work' => 123125, 'time_close' => 123126,
//       'description' => 'Проравло трубу', 'number' => 289,
//       'contact_fio' => 'Некрасов Евгений', 'contact_telephone' => 34534,
//       'contact_cellphone' => 89222944742, 'department' => $this->department,
//       'house' => $this->house, 'type' => $this->type
//       ];
//     $pimple = new \Pimple\Container();
//     $pimple['factory_department'] = function($p){
//       return new factory_department();
//     };
//     $pimple['factory_street'] = function($p){
//       return new factory_street();
//     };
//     $pimple['factory_house'] = function($p){
//       return new factory_house();
//     };
//     $pimple['factory_query'] = function($p){
//       return new factory_query();
//     };
//     di::set_instance($pimple);
//   }

//   public function test_find_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_query($this->pdo, $this->company))
//       ->find(123);
//   }

//   public function test_find_2(){
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
//     (new mapper_query($this->pdo, $this->company))
//       ->find(123);
//   }

//   public function test_find_3(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->once())
//       ->method('rowCount')
//       ->will($this->returnValue(0));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $query = (new mapper_query($this->pdo, $this->company))
//       ->find(123);
//     $this->assertNull($query);
//   }

//   public function test_find_4(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->once())
//       ->method('rowCount')
//       ->will($this->returnValue(1));
//     $this->stmt->expects($this->once())
//       ->method('fetch')
//       ->will($this->returnValue($this->query_row));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $query = (new mapper_query($this->pdo, $this->company))
//       ->find(123);
//     $this->assertInstanceOf('data_query',  $query);
//   }

//   public function test_get_queries_by_number_2(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->exactly(3))
//       ->method('fetch')
//       ->will($this->onConsecutiveCalls($this->query_row,
//       $this->query_row, false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $queries = (new mapper_query($this->pdo, $this->company))
//       ->get_queries_by_number(5633);
//     $this->assertEquals(2, count($queries));
//   }

//   public function test_get_insert_id_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_query($this->pdo, $this->company))
//       ->get_insert_id();
//   }

//   public function test_get_insert_id_2(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->once())
//       ->method('rowCount')
//       ->will($this->returnValue(0));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_query($this->pdo, $this->company))
//       ->get_insert_id();
//   }

//   public function test_get_insert_id_3(){
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
//     (new mapper_query($this->pdo, $this->company))
//       ->get_insert_id();
//   }

//   public function test_get_insert_id_4(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->once())
//       ->method('rowCount')
//       ->will($this->returnValue(1));
//     $this->stmt->expects($this->once())
//       ->method('fetch')
//       ->will($this->returnValue(['max_query_id' => 1]));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $id = (new mapper_query($this->pdo, $this->company))
//       ->get_insert_id();
//     $this->assertEquals(2, $id);
//   }

//   public function test_get_insert_query_number_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_query($this->pdo, $this->company))
//       ->get_insert_query_number(123123);
//   }

//   public function test_get_insert_query_number_2(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->once())
//       ->method('rowCount')
//       ->will($this->returnValue(0));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_query($this->pdo, $this->company))
//       ->get_insert_query_number(123123);
//   }

//   public function test_get_insert_query_number_3(){
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
//     (new mapper_query($this->pdo, $this->company))
//       ->get_insert_query_number(123123);
//   }

//   public function test_get_insert_query_number_4(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->once())
//       ->method('rowCount')
//       ->will($this->returnValue(1));
//     $this->stmt->expects($this->once())
//       ->method('fetch')
//       ->will($this->returnValue(['querynumber' => 123]));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $number = (new mapper_query($this->pdo, $this->company))
//       ->get_insert_query_number(123123);
//     $this->assertEquals(124, $number);
//   }

//   public function test_get_queries_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_query($this->pdo, $this->company))
//       ->get_queries($this->params);
//   }

//   public function test_get_queries_2(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->exactly(3))
//       ->method('fetch')
//       ->will($this->onConsecutiveCalls($this->query_row,
//       $this->query_row, false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $queries = (new mapper_query($this->pdo, $this->company))
//       ->get_queries($this->params);
//     $this->assertEquals(2, count($queries));
//   }

//   public function test_get_queries_by_house_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_query($this->pdo, $this->company))
//       ->get_queries_by_house($this->house);
//   }

//   public function test_get_queries_by_house_2(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->stmt->expects($this->exactly(3))
//       ->method('fetch')
//       ->will($this->onConsecutiveCalls($this->query_row,
//       $this->query_row, false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     $queries = (new mapper_query($this->pdo, $this->company))
//       ->get_queries_by_house($this->house);
//     $this->assertEquals(2, count($queries));
//   }

//   public function test_update_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_query($this->pdo, $this->company))
//       ->update($this->query);
//   }

//   public function test_update_2(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_query($this->pdo, $this->company))
//       ->update($this->query);
//   }

//   public function test_insert_1(){
//     $this->setExpectedException('RuntimeException');
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(false));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_query($this->pdo, $this->company))
//       ->insert($this->query);
//   }

//   public function test_insert_2(){
//     $this->stmt->expects($this->once())
//       ->method('execute')
//       ->will($this->returnValue(true));
//     $this->pdo->expects($this->once())
//       ->method('prepare')
//       ->will($this->returnValue($this->stmt));
//     (new mapper_query($this->pdo, $this->company))
//       ->insert($this->query);
//   }
// }