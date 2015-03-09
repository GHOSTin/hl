<?php

use \domain\query2comment;
use \domain\query;
use \domain\department;
use \domain\house;

class query_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->query = new query();
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

  public function test_set_warning_status_1(){
    $this->setExpectedException('DomainException');
    $this->query->set_warning_status('wrong');
  }

  public function test_set_warning_status_2(){
    $this->query->set_warning_status('hight');
    $this->assertEquals('hight', $this->query->get_warning_status());
    $this->query->set_warning_status('normal');
    $this->assertEquals('normal', $this->query->get_warning_status());
    $this->query->set_warning_status('planned');
    $this->assertEquals('planned', $this->query->get_warning_status());
  }

  public function test_set_payment_status_1(){
    $this->setExpectedException('DomainException');
    $this->query->set_payment_status('wrong');
  }

  public function test_set_payment_status_2(){
    $this->query->set_payment_status('paid');
    $this->assertEquals('paid', $this->query->get_payment_status());
    $this->query->set_payment_status('unpaid');
    $this->assertEquals('unpaid', $this->query->get_payment_status());
    $this->query->set_payment_status('recalculation');
    $this->assertEquals('recalculation', $this->query->get_payment_status());
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
    $this->query->set_reopen_status();
    $this->query->close(1397562800, 'Причина закрытия');
  }

  public function test_close_query_2(){
    $this->query->set_open_status();
    $this->query->close(1397562800, 'Причина закрытия');
    $this->assertEquals(1397562800, $this->query->get_time_close());
    $this->assertEquals('Причина закрытия', $this->query->get_close_reason());
    $this->assertEquals('close', $this->query->get_status());
  }

  public function test_reclose_1(){
    $this->setExpectedException('DomainException');
    $this->query->set_open_status();
    $this->query->reclose();
  }

  public function test_reclose_2(){
    $this->query->set_reopen_status();
    $this->query->reclose();
    $this->assertEquals('close', $this->query->get_status());
  }

  public function test_reopen_1(){
    $this->setExpectedException('DomainException');
    $this->query->set_open_status();
    $this->query->reopen();
  }

  public function test_reopen_2(){
    $this->query->set_close_status();
    $this->query->reopen();
    $this->assertEquals('reopen', $this->query->get_status());
  }

  public function test_set_close_status(){
    $this->query->set_close_status();
    $this->assertEquals('close', $this->query->get_status());
  }

  public function test_set_open_status(){
    $this->query->set_open_status();
    $this->assertEquals('open', $this->query->get_status());
  }

  public function test_set_reopen_status(){
    $this->query->set_reopen_status();
    $this->assertEquals('reopen', $this->query->get_status());
  }

  public function test_set_work_status(){
    $this->query->set_work_status();
    $this->assertEquals('working', $this->query->get_status());
  }

  public function test_to_work_query_1(){
    $this->setExpectedException('DomainException');
    $this->query->set_close_status();
    $this->query->to_work(1397562800);
  }

  public function test_to_work_query_2(){
    $this->query->set_open_status();
    $this->query->to_work(1397562800);
    $this->assertEquals(1397562800, $this->query->get_time_work());
    $this->assertEquals('working', $this->query->get_status());
  }
}