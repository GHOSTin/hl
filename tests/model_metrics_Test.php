<?php

/**
 * @property Pimple pimple
 * @property PHPUnit_Framework_MockObject_MockObject|PDOStatement stmt
 * @property PHPUnit_Framework_MockObject_MockObject|pdo_mock pdo
 * @property data_metrics metrics
 */
class model_metrics_Test extends PHPUnit_Framework_TestCase {

  protected function setUp(){
    $this->pimple = new Pimple();
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->metrics = new data_metrics();
    $this->pimple['factory_metrics'] = function($p){
      return new factory_metrics();
    };
  }

  public function test_remove(){
    $this->pdo->expects($this->once())
        ->method('beginTransaction');
    $this->pdo->expects($this->once())
        ->method('commit');
    $this->pimple['pdo'] = $this->pdo;
    $this->pimple['mapper_metrics'] = function($p){
      $mapper = $this->getMockBuilder('mapper_metrics')
          ->disableOriginalConstructor()
          ->getMock();
      $mapper->expects($this->exactly(2))
          ->method('delete')
          ->will($this->returnValue('bef03c09ee267d6ae1df235dc3595f6953cbe24e'));
      return $mapper;
    };
    di::set_instance($this->pimple);
    $result = (new model_metrics())
        ->remove(['bef03c09ee267d6ae1df235dc3595f6953cbe24e', 'cdea064b40550dc432148efc7e2dbbd03798f864']);
    $this->assertNotEmpty($result);
    $this->assertContains('bef03c09ee267d6ae1df235dc3595f6953cbe24e', $result);
    $this->assertContainsOnly('string', $result);
    $this->assertCount(2, $result);
  }
}
 