<?php namespace tests\main\models;

use Silex\Application;
use main\models\number_request as model;
use PHPUnit_Framework_TestCase;

class number_request_Test extends PHPUnit_Framework_TestCase{

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

  public function test_count(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('query/count_requests.tpl',
                      [
                        'number_requests' => 'requests_array',
                        'user' => $this->user
                      ])
               ->willReturn('render_template');
    $model = $this->getMockBuilder('main\models\number_request')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user])
                  ->setMethods(['get_requests'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_requests')
          ->willReturn('requests_array');
    $this->assertEquals('render_template', $model->count());
  }

  public function test_get_requests(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByQuery'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByQuery')
               ->with(null, ['time' => 'ASC'])
               ->willReturn('requests_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\number_request')
               ->willReturn($repository);
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('requests_array', $model->get_requests());
  }

  public function test_requests(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('query/requests.tpl',
                      [
                        'number_requests' => 'requests_array',
                        'user' => $this->user
                      ])
               ->willReturn('render_template');
    $model = $this->getMockBuilder('main\models\number_request')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, ])
                  ->setMethods(['get_requests'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_requests')
          ->willReturn('requests_array');
    $this->assertEquals('render_template', $model->requests());
  }
}