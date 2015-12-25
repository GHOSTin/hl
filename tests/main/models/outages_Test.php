<?php namespace tests\main\models;

use Silex\Application;
use main\models\outages as model;
use PHPUnit_Framework_TestCase;

class outages_Test extends PHPUnit_Framework_TestCase{

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
                       ->setMethods(['active'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('active')
               ->willReturn('outages_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\outage')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('outages/default_page.tpl', [
                                                    'outages' => 'outages_array',
                                                    'user' => $this->user
                                                  ])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
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
    $model = new model($this->twig, $this->em, $this->user);
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
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('render_template', $model->houses(250));
  }

  public function test_active(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['active'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('active')
               ->willReturn('outages_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\outage')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('outages/outages.tpl', [
                                                'outages' => 'outages_array',
                                                'user' => $this->user
                                              ])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals(['outages' => 'render_template'], $model->active());
  }

  public function test_today(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['today'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('today')
               ->willReturn('outages_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\outage')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('outages/outages.tpl', [
                                                'outages' => 'outages_array',
                                                'user' => $this->user
                                              ])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals(['outages' => 'render_template'], $model->today());
  }

  public function test_yesterday(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['yesterday'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('yesterday')
               ->willReturn('outages_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\outage')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('outages/outages.tpl', [
                                                'outages' => 'outages_array',
                                                'user' => $this->user
                                              ])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals(['outages' => 'render_template'], $model->yesterday());
  }

  public function test_week(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['week'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('week')
               ->willReturn('outages_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\outage')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('outages/outages.tpl', [
                                                'outages' => 'outages_array',
                                                'user' => $this->user
                                              ])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals(['outages' => 'render_template'], $model->week());
  }

  public function test_lastweek(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['lastweek'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('lastweek')
               ->willReturn('outages_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\outage')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('outages/outages.tpl', [
                                                'outages' => 'outages_array',
                                                'user' => $this->user
                                              ])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals(['outages' => 'render_template'], $model->lastweek());
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
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('render_template', $model->users(250));
  }
}