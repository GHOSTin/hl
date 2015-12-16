<?php

use Silex\Application;
use main\models\events as model;

class main_model_events_Test extends PHPUnit_Framework_TestCase{

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
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByTime'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByTime')
               ->with(strtotime('12:00'))
               ->willReturn('events_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\number2event')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('events/default_page.tpl', [
                                                    'events' => 'events_array',
                                                    'user' => $this->user
                                                  ])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals(['workspace' => 'render_template'], $model->default_page());
  }

  public function test_get_day_events(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByTime'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByTime')
               ->with(472460400)
               ->willReturn('events_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\number2event')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('events/events.tpl', [
                                              'events' => 'events_array',
                                              'user' => $this->user
                                            ])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals(['workspace' => 'render_template'], $model->get_day_events('21-12-1984'));
  }
}