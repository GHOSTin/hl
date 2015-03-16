<?php

use domain\work;

class work_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->work = new work();
  }

  public function test_get_status(){
    $reflection = new ReflectionClass('domain\work');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $this->assertEquals('active', $status->getValue($this->work));
  }

  public function test_get_id(){
    $reflection = new ReflectionClass('domain\work');
    $id = $reflection->getProperty('id');
    $id->setAccessible(true);
    $id->setValue($this->work, 125);
    $this->assertEquals(125, $this->work->get_id());
  }

  public function test_new_instance(){
    $work = work::new_instance('Привет');
    $reflection = new ReflectionClass('domain\work');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $this->assertEquals('active', $status->getValue($work));
    $this->assertEquals('Привет', $work->get_name());
  }

  public function test_set_name_1(){
    $this->work->set_name('Привет -Я');
    $this->assertEquals('Привет -Я', $this->work->get_name());
  }

  public function test_set_name_2(){
    $this->setExpectedException('DomainException');
    $this->work->set_name('Kachestvennie usligu');
  }

  public function test_set_name_3(){
    $this->setExpectedException('DomainException');
    $this->work->set_name(str_repeat('ПРиветия', 32));
  }

  public function test_set_name_4(){
    $this->setExpectedException('DomainException');
    $this->work->set_name('');
  }
}