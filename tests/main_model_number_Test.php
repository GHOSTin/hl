<?php

use Silex\Application;
use main\models\number as model;

class main_model_number_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->user = $this->getMock('domain\user');
  }

  public function test_get_houses_by_street(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findBy'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with(['street' => 123])
               ->will($this->returnValue([1, 2]));
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\house')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->user);
    $this->assertEquals([1, 2], $model->get_houses_by_street(123));
  }
}