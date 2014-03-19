<?php

class controller_query_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->pimple = new Pimple();
    $this->user = new data_user();
    $this->company = new data_company();
    $this->request = new model_request();
    $this->query = new data_query();
    $this->group = new data_group();
    $this->workgroup = new data_workgroup();
    $this->street = new data_street();
    $this->query_work_type = new data_query_work_type();
    $this->work = new data_work();
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

  public function test_private_get_dialog_cancel_client_query(){
    $res = controller_query::private_get_dialog_cancel_client_query($this->request);
    $this->assertNull($res);
  }

  public function test_private_get_dialog_add_user(){
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
    $this->pimple['model_group'] = function($p){
      $model = $this->getMockBuilder('model_group')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_groups')
        ->will($this->returnValue([$this->group]));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10];
    $array = controller_query::private_get_dialog_add_user($this->request);
    $this->assertSame($this->query, $array['query']);
    $this->assertSame($this->group, $array['groups'][0]);
  }

  public function test_private_get_dialog_add_work(){
    $this->pimple['model_workgroup'] = function($p){
      $model = $this->getMockBuilder('model_workgroup')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_workgroups')
        ->will($this->returnValue([$this->workgroup]));
      return $model;
    };
    di::set_instance($this->pimple);
    $array = controller_query::private_get_dialog_add_work($this->request);
    $this->assertSame($this->workgroup, $array['workgroups'][0]);
  }

  public function test_private_get_dialog_create_query(){
    $res = controller_query::private_get_dialog_create_query($this->request);
    $this->assertNull($res);
  }

  public function test_private_get_dialog_close_query(){
    $res = controller_query::private_get_dialog_close_query($this->request);
    $this->assertNull($res);
  }

  public function test_private_get_dialog_reclose_query(){
    $res = controller_query::private_get_dialog_reclose_query($this->request);
    $this->assertNull($res);
  }

  public function test_private_get_dialog_reopen_query(){
    $res = controller_query::private_get_dialog_reopen_query($this->request);
    $this->assertNull($res);
  }

  public function test_private_get_dialog_to_working_query(){
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
    $array = controller_query::private_get_dialog_to_working_query($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_dialog_change_initiator(){
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
    $this->pimple['model_street'] = function($p){
      $model = $this->getMock('model_street');
      $model->expects($this->once())
        ->method('get_streets')
        ->will($this->returnValue([$this->street]));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10];
    $array = controller_query::private_get_dialog_change_initiator($this->request);
    $this->assertSame($this->query, $array['query']);
    $this->assertSame($this->street, $array['streets'][0]);
  }

  public function test_private_get_dialog_edit_description(){
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
    $array = controller_query::private_get_dialog_edit_description($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_dialog_edit_reason(){
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
    $array = controller_query::private_get_dialog_edit_reason($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_dialog_edit_contact_information(){
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
    $array = controller_query::private_get_dialog_edit_contact_information($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_dialog_edit_payment_status(){
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
    $array = controller_query::private_get_dialog_edit_payment_status($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_dialog_edit_warning_status(){
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
    $array = controller_query::private_get_dialog_edit_warning_status($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_dialog_edit_work_type(){
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

    $this->pimple['model_query_work_type'] = function($p){
      $model = $this->getMockBuilder('model_query_work_type')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_query_work_types')
        ->will($this->returnValue([$this->query_work_type]));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10];
    $array = controller_query::private_get_dialog_edit_work_type($this->request);
    $this->assertSame($this->query, $array['query']);
    $this->assertSame($this->query_work_type, $array['work_types'][0]);
  }

  public function test_private_get_dialog_initiator(){
    $this->pimple['model_street'] = function($p){
      $model = $this->getMock('model_street');
      $model->expects($this->once())
        ->method('get_streets')
        ->will($this->returnValue([$this->street]));
      return $model;
    };
    di::set_instance($this->pimple);
    $array = controller_query::private_get_dialog_initiator($this->request);
    $this->assertSame($this->street, $array['streets'][0]);
  }

  public function test_private_get_dialog_remove_work(){
    $this->pimple['model_work'] = function($p){
      $model = $this->getMockBuilder('model_work')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_work')
        ->with(10)
        ->will($this->returnValue($this->work));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['work_id' => 10];
    $array = controller_query::private_get_dialog_remove_work($this->request);
    $this->assertSame($this->work, $array['work']);
  }

  public function test_private_print_query(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_query')
        ->with(10)
        ->will($this->returnValue($this->query));
      $model->expects($this->once())
        ->method('init_users');
      $model->expects($this->once())
        ->method('init_numbers');
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10];
    $array = controller_query::private_print_query($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_query_content(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_query')
        ->with(10)
        ->will($this->returnValue($this->query));
      $model->expects($this->once())
        ->method('init_users');
      $model->expects($this->once())
        ->method('init_numbers');
      $model->expects($this->once())
        ->method('init_comments');
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10];
    $array = controller_query::private_get_query_content($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_query_title(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_query')
        ->with(10)
        ->will($this->returnValue($this->query));
      $model->expects($this->once())
        ->method('init_numbers');
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10];
    $array = controller_query::private_get_query_title($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_query_numbers(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_query')
        ->with(10)
        ->will($this->returnValue($this->query));
      $model->expects($this->once())
        ->method('init_numbers');
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10];
    $array = controller_query::private_get_query_numbers($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_query_users(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_query')
        ->with(10)
        ->will($this->returnValue($this->query));
      $model->expects($this->once())
        ->method('init_users');
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10];
    $array = controller_query::private_get_query_users($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_query_works(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_query')
        ->with(10)
        ->will($this->returnValue($this->query));
      $model->expects($this->once())
        ->method('init_works');
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10];
    $array = controller_query::private_get_query_works($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_query_comments(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_query')
        ->with(10)
        ->will($this->returnValue($this->query));
      $model->expects($this->once())
        ->method('init_comments');
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10];
    $array = controller_query::private_get_query_comments($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_get_search(){
    $res = controller_query::private_get_search($this->request);
    $this->assertNull($res);
  }

  public function test_private_remove_user(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('remove_user')
        ->with(10, 15, 'manager')
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10, 'user_id' => 15, 'type' => 'manager'];
    $array = controller_query::private_remove_user($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_remove_work(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('remove_work')
        ->with(10, 15)
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10, 'work_id' => 15];
    $array = controller_query::private_remove_work($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_update_description(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('update_description')
        ->with(10, 'Привет')
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10, 'description' => 'Привет'];
    $array = controller_query::private_update_description($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_update_reason(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('update_reason')
        ->with(10, 'Привет')
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10, 'reason' => 'Привет'];
    $array = controller_query::private_update_reason($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_update_contact_information(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('update_contact_information')
        ->with(10, 'Некрасов', 83439647957, 89222944742)
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10, 'fio' => 'Некрасов', 'telephone' => 83439647957,
     'cellphone' => 89222944742];
    $array = controller_query::private_update_contact_information($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_update_payment_status(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('update_payment_status')
        ->with(10, 'unpaid')
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10, 'status' => 'unpaid'];
    $array = controller_query::private_update_payment_status($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_update_warning_status(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('update_warning_status')
        ->with(10, 'normal')
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10, 'status' => 'normal'];
    $array = controller_query::private_update_warning_status($this->request);
    $this->assertSame($this->query, $array['query']);
  }

  public function test_private_update_work_type(){
    $this->pimple['model_query'] = function($p){
      $model = $this->getMockBuilder('model_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('update_work_type')
        ->with(10, 'elect')
        ->will($this->returnValue($this->query));
      return $model;
    };
    di::set_instance($this->pimple);
    $_GET = ['id' => 10, 'type' => 'elect'];
    $array = controller_query::private_update_work_type($this->request);
    $this->assertSame($this->query, $array['query']);
  }
}