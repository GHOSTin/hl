<?php namespace tests\main\models;

use Silex\Application;
use main\models\report_user_access as model;
use PHPUnit_Framework_TestCase;

class report_user_access_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMockBuilder('domain\user')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->app = new Application();
  }

  public function test_report_1(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(false);
    $model = new model($this->em, $this->twig, $this->user);
    $model->report();
  }

  public function test_get_requests(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findBy'])
                       ->getMock();
    $repository->expects($this->exactly(3))
               ->method('findBy')
               ->withConsecutive(
                                  [[], ['lastname' => 'ASC']],
                                  [[], ['name' => 'ASC']],
                                  [[], ['name' => 'ASC']]
                                )
               ->will($this->onConsecutiveCalls(
                                                'users_array',
                                                'departments_array',
                                                'categories_array'
                                                ));
    $this->em->expects($this->exactly(3))
               ->method('getRepository')
               ->withConsecutive(
                                ['domain\user'],
                                ['domain\department'],
                                ['domain\workgroup']
                                )
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('report_user_access\report.tpl', [
                                                        'users' => 'users_array',
                                                        'departments' => 'departments_array',
                                                        'categories' => 'categories_array',
                                                        'user' => $this->user
                                                       ])
               ->willReturn('render_template');
    $model = new model($this->em, $this->twig, $this->user);
    $this->assertEquals('render_template', $model->report());
  }
}