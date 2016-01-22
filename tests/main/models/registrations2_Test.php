<?php namespace tests\main\models;

use Silex\Application;
use main\models\registrations as model;
use PHPUnit_Framework_TestCase;

class registrations2_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->logger = $this->getMockBuilder('Monolog\Logger')
                         ->disableOriginalConstructor()
                         ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->app = new Application();
    $this->model = new model($this->twig, $this->em, $this->user, $this->logger);
  }

  public function test_default_page(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('registrations/default_page.tpl')
               ->willReturn('render_template');
    $this->assertEquals('render_template', $this->model->default_page());
  }

  public function test_get_open_requests(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with([])
               ->willReturn('requests_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\registration_request')
             ->will($this->returnValue($repository));
    $this->assertEquals('requests_array', $this->model->get_open_requests());
  }
}