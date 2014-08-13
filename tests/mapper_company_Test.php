<?php

class mapper_company_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->pdo = $this->getMock('pdo_mock');
    $this->stmt = $this->getMock('PDOStatement');
    $this->pimple = new \Pimple\Container();
    $this->row = ['id' => 1, 'name' => 'Наш город'];
  }

  public function test_find_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_company($this->pdo))
      ->find(125);
  }

    public function test_find_2(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(2));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_company($this->pdo))
      ->find(125);
  }

  public function test_find_3(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(0));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $company = (new mapper_company($this->pdo))
      ->find(125);
    $this->assertNull($company);
  }

  public function test_find_4(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->once())
      ->method('rowCount')
      ->will($this->returnValue(1));
    $this->stmt->expects($this->once())
      ->method('fetch')
      ->will($this->returnValue($this->row));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->pimple['factory_company'] = function($p){
      $factory = $this->getMock('factory_company');
      $factory->expects($this->once())
        ->method('build')
        ->will($this->returnValue(new data_company()));
      return $factory;
    };
    di::set_instance($this->pimple);
    $company = (new mapper_company($this->pdo))
      ->find(125);
    $this->assertInstanceOf('data_company', $company);
  }

  public function test_find_all_1(){
    $this->setExpectedException('RuntimeException');
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    (new mapper_company($this->pdo))
      ->find_all();
  }

  public function test_4_find_all(){
    $this->stmt->expects($this->once())
      ->method('execute')
      ->will($this->returnValue(true));
    $this->stmt->expects($this->exactly(3))
      ->method('fetch')
      ->will($this->onConsecutiveCalls($this->row, $this->row, false));
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($this->stmt));
    $this->pimple['factory_company'] = function($p){
      $factory = $this->getMock('factory_company');
      $factory->expects($this->exactly(2))
        ->method('build')
        ->will($this->returnValue(new data_company()));
      return $factory;
    };
    di::set_instance($this->pimple);
    $companies = (new mapper_company($this->pdo))
      ->find_all();
    $this->assertCount(2, $companies);
    $this->assertContainsOnlyInstancesOf('data_company', $companies);
  }
}