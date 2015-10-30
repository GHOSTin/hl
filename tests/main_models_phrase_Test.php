<?php

use Silex\Application;
use main\models\phrase as model;

class main_model_phrase_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->phrase = $this->getMock('domain\phrase');
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
             ->with('domain\phrase', 125)
             ->willReturn(null);
    new model($this->twig, $this->em, $this->user, 125);
  }

  public function test_remove_phrase_dialog(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\phrase', 125)
             ->willReturn($this->phrase);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('phrase\remove_phrase_dialog.tpl', ['phrase' => $this->phrase]);
    $model = new model($this->twig, $this->em, $this->user, 125);
    $model->remove_phrase_dialog();
  }

  public function test_remove(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\phrase', 125)
             ->willReturn($this->phrase);
    $this->em->expects($this->once())
             ->method('remove')
             ->with($this->phrase);
    $model = new model($this->twig, $this->em, $this->user, 125);
    $model->remove();
  }
}