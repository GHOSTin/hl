<?php

use main\models\repository as model;

class main_models_repository_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->user = $this->getMockBuilder('domain\user')
                       ->disableOriginalConstructor()
                       ->getMock();
  }

  public function test_get_workgroup_1(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 125)
             ->willReturn('workgroup_object');
    $model = new model($this->em, $this->user);
    $this->assertEquals('workgroup_object', $model->get_workgroup(125));
  }

  public function test_get_workgroup_2(){
    $this->setExpectedException('RuntimeException');
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 125)
             ->willReturn(null);
    $model = new model($this->em, $this->user);
    $model->get_workgroup(125);
  }
}