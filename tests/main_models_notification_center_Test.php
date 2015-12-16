<?php

use Silex\Application;
use main\models\notification_center as model;

class main_model_notification_center_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->app = new Application();
  }

  public function test_get_content(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('notification_center/get_content.tpl', ['users' => 'users_array'])
               ->willReturn('render_template');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with([], ['lastname' => 'ASC'])
               ->willReturn('users_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\user')
               ->willReturn($repository);
    $model = new model($this->twig, $this->em);
    $this->assertEquals('render_template', $model->get_content());
  }
}