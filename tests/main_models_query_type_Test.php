<?php

use Silex\Application;
use main\models\query_type as model;

class main_models_query_type_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->type = $this->getMock('domain\query_type');
    $this->app = new Application();
  }

  public function test_construct_1(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(false);
    new model($this->em, $this->user, 125);
  }

  public function test_construct_2(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\query_type', 125)
             ->willReturn(null);
    new model($this->em, $this->user, 125);
  }

  public function test_update_color(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\query_type', 125)
             ->willReturn($this->type);
    $this->em->expects($this->once())
             ->method('flush');
    $this->type->expects($this->once())
               ->method('set_color')
               ->with('cccccc');
    $model = new model($this->em, $this->user, 125);
    $model->update_color('cccccc');
  }
}