<?php

/**
 * @property data_metrics metrics
 * @property PHPUnit_Framework_MockObject_MockObject|PDOStatement stmt
 * @property PHPUnit_Framework_MockObject_MockObject|pdo_mock pdo
 * @property array row
 */
class mapper_metrics_Test extends PHPUnit_Framework_TestCase {

  protected function setUp(){
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->metrics = new data_metrics();
    $pimple = new Pimple();
    $pimple['factory_metrics'] = function($p){
      return new factory_metrics();
    };
    di::set_instance($pimple);
    $this->row = ['id' => 'bef03c09ee267d6ae1df235dc3595f6953cbe24e',
        'address' => "Вайнера 75", 'metrics' => "ХВС: 12"
    ];
  }

  public function test_find_all_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
        ->method('execute')
        ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_metrics($this->pdo))
        ->find_all();
  }

  public function test_find_all_2(){
    $this->stmt->expects($this->once())
        ->method('execute')
        ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
        ->method('fetch')
        ->will($this->onConsecutiveCalls($this->row,
            $this->row, false));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    $metrics = (new mapper_metrics($this->pdo))->find_all();
    $this->assertCount(2, $metrics);
    $this->assertContainsOnlyInstancesOf('data_metrics', $metrics);
  }

  public function test_delete_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(false));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    (new mapper_metrics($this->pdo))->delete('bef03c09ee267d6ae1df235dc3595f6953cbe24e');
  }

  public function test_delete_2(){
    $this->stmt->expects($this->once())
        ->method('execute')->will($this->returnValue(true));
    $this->pdo->expects($this->once())
        ->method('prepare')
        ->will($this->returnValue($this->stmt));
    $result = (new mapper_metrics($this->pdo))->delete('bef03c09ee267d6ae1df235dc3595f6953cbe24e');
    $this->assertEquals('bef03c09ee267d6ae1df235dc3595f6953cbe24e', $result);
  }

}
 