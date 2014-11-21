<?php

class model_number_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->pimple = new \Pimple\Container();
    $this->em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()->getMock();
  }

  public function test_private_show_default_page(){
    $this->pimple['em'] = function(){
      $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                         ->disableOriginalConstructor()
                         ->setMethods(['findByStreet'])->getMock();
      $repository->expects($this->once())
          ->method('findByStreet')
          ->with(125)
          ->will($this->returnValue([1, 2]));
      $this->em->expects($this->once())
        ->method('getRepository')
        ->with('data_house')
        ->will($this->returnValue($repository));
      return $this->em;
    };
    $model = new model_number($this->pimple);
    $this->assertEquals([1, 2], $model->get_houses_by_street(125));
  }
}