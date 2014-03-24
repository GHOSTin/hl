<?php

class model_query_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->pimple = new Pimple();
    $this->pimple['profile'] = null;
    $this->company = new data_company();
  }

  public function test_set_street_1(){
    $this->pimple['model_street'] = function($p){
      $street = $this->getMock('data_street');
      $street->expects($this->once())
      ->method('get_id');
      $model = $this->getMock('model_street');
      $model->expects($this->once())
        ->method('get_street')
        ->with(5)
        ->will($this->returnValue($street));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->set_street(5);
  }

  public function test_set_street_2(){
    $this->pimple['model_street'] = function($p){
      $model = $this->getMock('model_street');
      $model->expects($this->never())
        ->method('get_street')
        ->with(0);
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->set_street(0);
  }

  public function test_set_time_open_begin(){
    (new model_query($this->company))->set_time_open_begin(12345678);
  }

  public function test_set_time_open_end(){
    (new model_query($this->company))->set_time_open_end(12345678);
  }

  public function test_set_house_1(){
    $this->pimple['model_house'] = function($p){
      $house = $this->getMock('data_house');
      $house->expects($this->once())
        ->method('get_id');
      $model = $this->getMock('model_house');
      $model->expects($this->once())
        ->method('get_house')
        ->with(5)
        ->will($this->returnValue($house));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->set_house(5);
  }

  public function test_set_house_2(){
    $this->pimple['model_house'] = function($p){
      $model = $this->getMock('model_house');
      $model->expects($this->never())
        ->method('get_house')
        ->with(0);
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->set_house(0);
  }

  public function test_set_work_type_1(){
    $this->pimple['model_query_work_type'] = function($p){
      $type = $this->getMock('data_query_work_type');
      $type->expects($this->once())
        ->method('get_id');
      $model = $this->getMockBuilder('model_query_work_type')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_query_work_type')
        ->with(5)
        ->will($this->returnValue($type));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->set_work_type(5);
  }

  public function test_set_work_type_2(){
    $this->pimple['model_query_work_type'] = function($p){
      $model = $this->getMockBuilder('model_query_work_type')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->never())
        ->method('get_query_work_type')
        ->with(0)
        ->will($this->returnValue($type));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->set_work_type(0);
  }

  public function test_add_comment_1(){
    $q2c = new data_query2comment();
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('add_comment')
      ->with($this->identicalTo($q2c));
    $this->pimple['user'] = new data_user();
    $this->pimple['factory_query2comment'] = function($p) use ($q2c){
      $model = $this->getMock('factory_query2comment');
      $model->expects($this->once())
        ->method('build')
        ->will($this->returnValue($q2c));
      return $model;
    };
    $this->pimple['mapper_query2comment'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query2comment')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('init_comments')
        ->with($this->identicalTo($this->company), $this->identicalTo($query));
      $model->expects($this->once())
        ->method('update')
        ->with($this->identicalTo($this->company), $this->identicalTo($query));
      return $model;
    };
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue($query));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->add_comment(5, 'Привет');
  }
}