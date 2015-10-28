<?php

use Silex\Application;
use main\models\workgroup as model;

class main_model_workgroup_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->workgroup = $this->getMock('domain\workgroup');
    $this->app = new Application();
  }

  public function test_construct_1(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(false);
    new model($this->twig, $this->em, $this->user, 125);
  }

  public function test_construct_2(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 125)
             ->willReturn(null);
    new model($this->twig, $this->em, $this->user, 125);
  }

  public function test_create_phrase_dialog(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 125)
             ->willReturn($this->workgroup);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('workgroup\create_phrase_dialog.tpl', ['workgroup' => $this->workgroup]);
    $model = new model($this->twig, $this->em, $this->user, 125);
    $model->create_phrase_dialog();
  }

  public function test_get_content(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 125)
             ->willReturn($this->workgroup);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('works\get_workgroup_content.tpl', ['workgroup' => $this->workgroup]);
    $model = new model($this->twig, $this->em, $this->user, 125);
    $model->get_content();
  }

  public function test_create_phrase(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 125)
             ->willReturn($this->workgroup);
    $this->em->expects($this->once())
             ->method('persist')
             ->with($this->isInstanceOf('domain\phrase'));
    $this->em->expects($this->once())
             ->method('flush');
    $model = new model($this->twig, $this->em, $this->user, 125);
    $model->create_phrase('Привет');
  }
}