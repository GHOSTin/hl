<?php

class model_error_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->pimple = new \Pimple\Container();
    $this->error = new data_error();
    $this->mapper = $this->getMockBuilder('mapper_error')
      ->disableOriginalConstructor()->getMock();
  }

  public function test_1_delete_error(){
    $this->pimple['mapper_error'] = function($p){
      $this->mapper->expects($this->once())
        ->method('find')
        ->will($this->returnValue($this->error));
      $this->mapper->expects($this->once())
        ->method('delete');
      return $this->mapper;
    };
    di::set_instance($this->pimple);
    (new model_error)->delete_error(123123, 2);
  }

  public function test_1_send_error(){
    $this->pimple['mapper_error'] = function($p){
      $this->mapper->expects($this->once())
        ->method('find')
        ->will($this->returnValue(null));
      $this->mapper->expects($this->once())
        ->method('insert');
      return $this->mapper;
    };
    $this->pimple['user'] = function($p){
      return new data_user();
    };
    $this->pimple['factory_error'] = function($p){
      return new factory_error();
    };
    di::set_instance($this->pimple);
    (new model_error)->send_error('Ошибка');
  }

  public function test_2_delete_error(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_error'] = function($p){
      $this->mapper->expects($this->once())
        ->method('find')
        ->will($this->returnValue(null));
      $this->mapper->expects($this->never())
        ->method('delete');
      return $this->mapper;
    };
    di::set_instance($this->pimple);
    (new model_error)->delete_error(123123, 2);
  }

  public function test_2_send_error(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_error'] = function($p){
      $this->mapper->expects($this->once())
        ->method('find')
        ->will($this->returnValue($this->error));
      $this->mapper->expects($this->never())
        ->method('insert');
      return $this->mapper;
    };
    $this->pimple['user'] = function($p){
      return new data_user();
    };
    $this->pimple['factory_error'] = function($p){
      return new factory_error();
    };
    di::set_instance($this->pimple);
    (new model_error)->send_error('Ошибка');
  }
}