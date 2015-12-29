<?php namespace tests\main\models;

use Silex\Application;
use main\models\system_search as model;
use PHPUnit_Framework_TestCase;

class system_search_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->app = new Application();
  }

  public function test_construct(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(false);
    new model($this->twig, $this->em, $this->user);
  }

  public function test_search_number_form(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('system/search_number_form.tpl', ['user' => $this->user])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('render_template', $model->search_number_form());
  }

  public function test_search_number(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByNumber'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByNumber')
               ->with(125)
               ->willReturn('number_object');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\number')
             ->will($this->returnValue($repository));
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('system/search_number.tpl',
                      [
                        'user' => $this->user,
                        'search' => 125,
                        'number' => 'number_object'
                      ])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('render_template', $model->search_number(125));
  }
}