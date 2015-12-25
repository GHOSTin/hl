<?php namespace tests\domain;

use domain\workgroup;
use domain\event;
use domain\work;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

class workgroup_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->workgroup = new workgroup();
  }

  public function test_add_event_1(){
    $event = new event();
    $this->workgroup->add_event($event);
    $this->assertSame($event, $this->workgroup->get_events()[0]);
    $this->workgroup->exclude_event($event);
    $this->assertTrue($this->workgroup->get_events()->isEmpty());
  }

  public function test_add_event_2(){
    $this->setExpectedException('DomainException');
    $event = new event();
    $this->workgroup->add_event($event);
    $this->workgroup->add_event($event);
  }

  public function test_add_work_1(){
    $work = new work();
    $this->workgroup->add_work($work);
    $this->assertSame($work, $this->workgroup->get_works()[0]);
    $this->workgroup->exclude_work($work);
    $this->assertTrue($this->workgroup->get_works()->isEmpty());
  }

  public function test_add_work_2(){
    $this->setExpectedException('DomainException');
    $work = new work();
    $this->workgroup->add_work($work);
    $this->workgroup->add_work($work);
  }

  public function test_get_events(){
    $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->workgroup->get_events());
  }

  public function test_get_status(){
    $reflection = new ReflectionClass('domain\workgroup');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $this->assertEquals('active', $status->getValue($this->workgroup));
  }

  public function test_get_id(){
    $reflection = new ReflectionClass('domain\workgroup');
    $id = $reflection->getProperty('id');
    $id->setAccessible(true);
    $id->setValue($this->workgroup, 125);
    $this->assertEquals(125, $this->workgroup->get_id());
  }

  public function test_get_works(){
    $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->workgroup->get_works());
  }

  public function test_get_phrases(){
    $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->workgroup->get_phrases());
  }

  public function test_new_instance(){
    $workgroup = workgroup::new_instance('Привет');
    $reflection = new ReflectionClass('domain\workgroup');
    $status = $reflection->getProperty('status');
    $status->setAccessible(true);
    $this->assertEquals('active', $status->getValue($workgroup));
    $this->assertEquals('Привет', $workgroup->get_name());
  }

  public function test_set_name_1(){
    $this->workgroup->set_name('Привет -Я');
    $this->assertEquals('Привет -Я', $this->workgroup->get_name());
  }

  public function test_set_name_2(){
    $this->setExpectedException('DomainException');
    $this->workgroup->set_name('Kachestvennie usligu');
  }

  public function test_set_name_3(){
    $this->setExpectedException('DomainException');
    $this->workgroup->set_name(str_repeat('ПРиветия', 32));
  }

  public function test_set_name_4(){
    $this->setExpectedException('DomainException');
    $this->workgroup->set_name('');
  }
}