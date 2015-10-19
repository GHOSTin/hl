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
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->willReturn('outages_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\outage')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('outages/default_page.tpl', ['outages' => 'outages_array'])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user, 125);
    $this->assertEquals(['workspace' => 'render_template'], $model->default_page());
  }

  public function test_dialog_create(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->em->expects($this->exactly(3))
               ->method('getRepository')
               ->withConsecutive(
                                  ['domain\workgroup'],
                                  ['domain\street'],
                                  ['domain\group']
                                )
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('outages/dialog_create.tpl')
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user, 125);
    $this->assertEquals('render_template', $model->dialog_create());
  }

  public function test_houses(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneById')
               ->with(250)
               ->willReturn('street_object');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\street')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('outages/houses.tpl', ['street' => 'street_object'])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user, 125);
    $this->assertEquals('render_template', $model->houses(250));
  }

  public function test_users(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneById')
               ->with(250)
               ->willReturn('group_object');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\group')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('outages/users.tpl', ['group' => 'group_object'])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user, 125);
    $this->assertEquals('render_template', $model->users(250));
  }
}