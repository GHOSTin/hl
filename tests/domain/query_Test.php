<?php namespace tests\domain;

use domain\query2comment;
use domain\query;
use domain\department;
use domain\house;
use domain\query_type;
use domain\user;
use domain\file;
use domain\number;
use domain\number_request;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

class query_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->query = new query();
    $this->user = new user();
    $this->path = '20150410/249d2c7dbd66604b90938aa1d7093f711ec4b77e.jpg';
    $this->time = time();
    $this->name = 'image.jpg';
  }

  public function test_add_comment_1(){
    $comment = new query2comment();
    $this->query->add_comment($comment);
    $this->assertSame($comment, $this->query->get_comments()[0]);
  }

  public function test_add_comment_2(){
    $this->setExpectedException('DomainException');
    $comment = new query2comment();
    $this->query->add_comment($comment);
    $this->query->add_comment($comment);
  }

  public function test_add_file_1(){
    $this->setExpectedException('DomainException');
    $this->query->close($this->user, time(), 'Причина');
    $file = new file($this->user, $this->path, $this->time, $this->name);
    $this->query->add_file($file);
  }

  public function test_add_file_2(){
    $file = new file($this->user, $this->path, $this->time, $this->name);
    $this->query->add_file($file);
    $this->assertSame($file, $this->query->get_files()[0]);
  }

  public function test_add_manager(){
    $this->query->add_manager($this->user);
    $this->assertCount(1, $this->query->get_managers());
    $q2u = $this->query->get_managers()[0];
    $this->assertInstanceOf('domain\query2user', $q2u);
    $this->assertEquals('manager', $q2u->get_class());
  }

  public function test_set_creator(){
    $this->query->set_creator($this->user);
    $this->assertCount(1, $this->query->get_users());
    $this->assertInstanceOf('domain\query2user', $this->query->get_creator());
    $this->assertEquals('creator', $this->query->get_creator()->get_class());
  }

  public function test_delete_file_1(){
    $this->setExpectedException('DomainException');
    $this->query->close($this->user,  time(), 'Причина');
    $file = new file($this->user, $this->path, $this->time, $this->name);
    $this->query->add_file($file);
    $q2f = $this->query->get_files()[0];
    $this->query->delete_file($q2f);
  }

  public function test_delete_file_2(){
    $file = new file($this->user, $this->path, $this->time, $this->name);
    $this->query->add_file($file);
    $q2f = $this->query->get_files()[0];
    $this->assertCount(1, $this->query->get_files());
    $this->query->delete_file($q2f);
    $this->assertCount(0, $this->query->get_files());
  }

  public function test_get_status(){
    $this->assertEquals('open', $this->query->get_status());
  }

  public function test_set_department(){
    $department = new department();
    $this->query->set_department($department);
    $this->assertSame($department, $this->query->get_department());
  }

  public function test_set_house(){
    $house = new house();
    $this->query->set_house($house);
    $this->assertSame($house, $this->query->get_house());
  }

  public function test_set_id(){
    $this->query->set_id(123);
    $this->assertEquals(123, $this->query->get_id());
  }

  public function test_set_time_open_1(){
    $this->query->set_time_open(1397562800);
    $this->assertEquals(1397562800, $this->query->get_time_open());
  }

  public function test_set_time_open_2(){
    $this->setExpectedException('DomainException');
    $this->query->set_time_open(0);
  }

  public function test_set_time_open_3(){
    $this->setExpectedException('DomainException');
    $this->query->set_time_open(-123);
  }

  public function test_query_type(){
    $query_type = new query_type();
    $this->query->set_query_type($query_type);
    $this->assertSame($query_type, $this->query->get_query_type());
  }

  public function test_set_request(){
    $this->assertNull($this->query->get_request());
    $number = new number();
    $request = new number_request($number, 'Описание запроса');
    $this->query->set_request($request);
    $this->assertSame($request, $this->query->get_request());
    $this->assertSame($this->query, $request->get_query());
  }

  public function test_set_time_work_1(){
    $this->query->set_time_open(1397562600);
    $this->query->set_time_work(1397562800);
    $this->assertEquals(1397562800, $this->query->get_time_work());
  }

  public function test_set_time_work_2(){
    $this->setExpectedException('DomainException');
    $this->query->set_time_open(1397562900);
    $this->query->set_time_work(1397562800);
  }

  public function test_set_contact_fio(){
    $this->query->set_contact_fio('Некрасов Евгений Валерьевич');
    $this->assertEquals('Некрасов Евгений Валерьевич', $this->query->get_contact_fio());
  }

  public function test_set_contact_telephone(){
    $this->query->set_contact_telephone('83439647957');
    $this->assertEquals('83439647957', $this->query->get_contact_telephone());
  }

  public function test_set_contact_cellphone(){
    $this->query->set_contact_cellphone('9222944742');
    $this->assertEquals('9222944742', $this->query->get_contact_cellphone());
  }

  public function test_set_initiator_1(){
    $this->query->set_initiator('number');
    $this->assertEquals('number', $this->query->get_initiator());
  }

  public function test_set_initiator_2(){
    $this->query->set_initiator('house');
    $this->assertEquals('house', $this->query->get_initiator());
  }

  public function test_set_initiator_3(){
    $this->setExpectedException('DomainException');
    $this->query->set_initiator('wrong');
  }

  public function test_close_query_1(){
    $this->setExpectedException('DomainException');
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'reopen');
    $this->query->close($this->user, 1397562800, 'Причина закрытия');
  }

  public function test_close_query_2(){
    $this->setExpectedException('DomainException');
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'close');
    $this->query->close($this->user, 1397562800, 'Причина закрытия');
  }

  public function test_close_query_3(){
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'open');
    $time = time();
    $this->query->close($this->user, $time, 'Причина закрытия');
    $this->assertEquals($time, $this->query->get_time_close());
    $this->assertEquals('Причина закрытия', $this->query->get_close_reason());
    $this->assertEquals('close', $this->query->get_status());
  }

  public function test_reclose_1(){
    $this->setExpectedException('DomainException');
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'open');
    $this->query->reclose($this->user);
  }

  public function test_reclose_2(){
    $this->setExpectedException('DomainException');
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'close');
    $this->query->reclose($this->user);
  }

  public function test_reclose_3(){
    $this->setExpectedException('DomainException');
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'working');
    $this->query->reclose($this->user);
  }

  public function test_reclose_4(){
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'reopen');
    $this->query->reclose($this->user);
    $this->assertEquals('close', $this->query->get_status());
  }

  public function test_reopen_1(){
    $this->setExpectedException('DomainException');
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'open');
    $this->query->reopen($this->user);
  }

  public function test_reopen_2(){
    $this->setExpectedException('DomainException');
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'working');
    $this->query->reopen($this->user);
  }

  public function test_reopen_3(){
    $this->setExpectedException('DomainException');
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'reopen');
    $this->query->reopen($this->user);
  }

  public function test_reopen_4(){
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'close');
    $this->query->reopen($this->user);
    $this->assertEquals('reopen', $this->query->get_status());
  }

  public function test_to_work_query_1(){
    $this->setExpectedException('DomainException');
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'working');
    $this->query->to_work(1397562800);
  }

  public function test_to_work_query_2(){
    $this->setExpectedException('DomainException');
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'close');
    $this->query->to_work(1397562800);
  }

  public function test_to_work_query_3(){
    $this->setExpectedException('DomainException');
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'reopen');
    $this->query->to_work(1397562800);
  }

  public function test_to_work_query_4(){
    $reflection = new ReflectionClass('domain\query');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $status->setValue($this->query, 'open');
    $time = time();
    $this->query->to_work($time);
    $this->assertEquals($time, $this->query->get_time_work());
    $this->assertEquals('working', $this->query->get_status());
  }
}