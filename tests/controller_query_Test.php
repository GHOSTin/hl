<?php

use \boxxy\classes\di;

class controller_query_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->request = new model_request();
    $this->pimple = new \Pimple\Container();
    $this->em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
      ->disableOriginalConstructor()->getMock();
  }

  public function test_private_get_dialog_add_work(){
    $this->request->set_property('id', 125);
    $this->pimple['em'] = function(){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(['findOneById'])
        ->getMock();
      $repository->expects($this->once())
        ->method('findOneById')
        ->with(125)
        ->will($this->returnValue('query_object'));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->with('data_query')
        ->will($this->returnValue($repository));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_query::private_get_dialog_add_work($this->request);
    $this->assertEquals('query_object', $response['query']);
  }

  public function test_private_get_query_content(){
    $this->request->set_property('id', 125);
    $this->pimple['em'] = function(){
      $this->em->expects($this->once())
        ->method('find')
        ->with('data_query', 125)
        ->will($this->returnValue('query_object'));
      return $this->em;
    };
    di::set_instance($this->pimple);
    $response = controller_query::private_get_query_content($this->request);
    $this->assertEquals('query_object', $response['query']);
  }
}