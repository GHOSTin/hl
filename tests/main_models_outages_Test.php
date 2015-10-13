<?php

use Silex\Application;
use main\models\outages as model;

class main_model_outages_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->app = new Application();
  }

  public function test_construct_1(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(false);
    new model($this->twig, $this->em, $this->user);
  }

  public function test_default_page(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('outages\default_page.tpl')
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user, 125);
    $this->assertEquals(['workspace' => 'render_template'], $model->default_page());
  }
}