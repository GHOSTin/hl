<?php

use \Silex\Application;
use \main\models\number as model;

class main_model_number_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()->getMock();
    $this->app = new Application();
    $this->app['em'] = $em;
  }

  public function test_get_houses_by_street(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByStreet'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByStreet')
               ->with(123)
               ->will($this->returnValue([1, 2]));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\house')
                    ->will($this->returnValue($repository));
    $model = new model($this->app);
    $model->get_houses_by_street(123);
  }
}