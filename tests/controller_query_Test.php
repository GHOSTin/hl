<?php

class controller_query_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->pimple = new Pimple();
    $this->user = new data_user();
    $this->company = new data_company();
    $this->request = new model_request();
    $this->query = new data_query();
  }

  public function test_private_add_user(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('add_user')
        ->with(1, 10, 'manager')
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 1, 'user_id' => 10, 'type' => 'manager'];
    $array = controller_query::private_add_user($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_add_comment(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('add_comment')
        ->with(1, 'Хорошего дня')
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['query_id' => 1, 'message' => 'Хорошего дня'];
    $array = controller_query::private_add_comment($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_add_work(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('add_work')
        ->with(1, 25, 1356063300, 1356153900)
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 1, 'work_id' => 25, 'begin_hours' => 10,
      'begin_minutes' => 15, 'begin_date' => '21.12.2012',
      'end_hours' => 11, 'end_minutes' => 25, 'end_date' => '22.12.2012'];
    $array = controller_query::private_add_work($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_close_query(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('close_query')
        ->with(1, 'Закрыта')
        ->will($this->returnValue($this->query));
      $model->expects($this->once())
        ->method('init_users');
      $model->expects($this->once())
        ->method('init_numbers');
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 1, 'reason' => 'Закрыта'];
    $array = controller_query::private_close_query($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_change_initiator(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('change_initiator')
        ->with(1, 25, 10)
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['query_id' => 1, 'house_id' => 25, 'number_id' => 10];
    $array = controller_query::private_change_initiator($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_reclose_query(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('reclose_query')
        ->with(1)
        ->will($this->returnValue($this->query));
      $model->expects($this->once())
        ->method('init_users');
      $model->expects($this->once())
        ->method('init_numbers');
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 1];
    $array = controller_query::private_reclose_query($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_reopen_query(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('reopen_query')
        ->with(1)
        ->will($this->returnValue($this->query));
      $model->expects($this->once())
        ->method('init_users');
      $model->expects($this->once())
        ->method('init_numbers');
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 1];
    $array = controller_query::private_reopen_query($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_to_working_query(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('to_working_query')
        ->with(1)
        ->will($this->returnValue($this->query));
      $model->expects($this->once())
        ->method('init_users');
      $model->expects($this->once())
        ->method('init_numbers');
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 1];
    $array = controller_query::private_to_working_query($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_documents(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_query')
        ->with(10)
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10];
    $array = controller_query::private_get_documents($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_dialog_add_comment(){
    $res = controller_query::private_get_dialog_add_comment($this->request);
    $this->assertNull($res);
  }
}