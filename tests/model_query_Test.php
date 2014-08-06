<?php

class model_query_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->pimple = new \Pimple\Container();
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

  public function test_close_query_1(){
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('get_status')
      ->will($this->returnValue('open'));
    $query->expects($this->once())
      ->method('set_status')
      ->with('close');
    $query->expects($this->once())
      ->method('set_close_reason')
      ->with('Причина закрытия');
    $query->expects($this->once())
      ->method('set_time_close');
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue($query));
      $model->expects($this->once())
        ->method('update');
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))
      ->close_query(5, 'Причина закрытия');
    $this->assertSame($query, $res);
  }

  public function test_close_query_2(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_query'] = function($p){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue(null));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->close_query(5, 'Причина закрытия');
  }

  public function test_close_query_3(){
    $this->setExpectedException('RuntimeException');
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('get_status')
      ->will($this->returnValue('close'));
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
    (new model_query($this->company))->close_query(5, 'Причина закрытия');
  }

  public function test_reclose_query_1(){
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('get_status')
      ->will($this->returnValue('reopen'));
    $query->expects($this->once())
      ->method('set_status')
      ->with('close');
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue($query));
      $model->expects($this->once())
        ->method('update');
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))->reclose_query(5);
    $this->assertSame($query, $res);
  }

  public function test_reclose_query_2(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_query'] = function($p){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue(null));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->reclose_query(5);
  }

  public function test_reclose_query_3(){
    $this->setExpectedException('RuntimeException');
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('get_status')
      ->will($this->returnValue('open'));
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
    (new model_query($this->company))->reclose_query(5);
  }

  public function test_reopen_query_1(){
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('get_status')
      ->will($this->returnValue('close'));
    $query->expects($this->once())
      ->method('set_status')
      ->with('reopen');
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue($query));
      $model->expects($this->once())
        ->method('update');
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))->reopen_query(5);
    $this->assertSame($query, $res);
  }

  public function test_reopen_query_2(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_query'] = function($p){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue(null));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->reopen_query(5);
  }

  public function test_reopen_query_3(){
    $this->setExpectedException('RuntimeException');
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('get_status')
      ->will($this->returnValue('open'));
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
    (new model_query($this->company))->reopen_query(5);
  }

  public function test_to_working_query_1(){
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('get_status')
      ->will($this->returnValue('open'));
    $query->expects($this->once())
      ->method('set_status')
      ->with('working');
    $query->expects($this->once())
      ->method('set_time_work');
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue($query));
      $model->expects($this->once())
        ->method('update');
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))->to_working_query(5);
    $this->assertSame($query, $res);
  }

  public function test_to_working_query_2(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_query'] = function($p){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue(null));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->to_working_query(5);
  }

  public function test_to_working_query_3(){
    $this->setExpectedException('RuntimeException');
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('get_status')
      ->will($this->returnValue('close'));
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
    (new model_query($this->company))->to_working_query(5);
  }

  public function test_get_query_1(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_query'] = function($p){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue(null));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->get_query(5);
  }

  public function test_get_query_2(){
    $query = new data_query();
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
    $res = (new model_query($this->company))->get_query(5);
    $this->assertSame($query, $res);
  }

  public function test_init_comments(){
    $query = new data_query();
    $this->pimple['mapper_query2comment'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query2comment')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('init_comments')
        ->with($this->identicalTo($this->company),
        $this->identicalTo($query));
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))->init_comments($query);
    $this->assertSame($query, $res);
  }

  public function test_get_queries_by_number(){
    $query = new data_query();
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_queries_by_number')
        ->with(4567)
        ->will($this->returnValue([$query, $query]));
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))->get_queries_by_number(4567);
    $this->assertCount(2, $res);
  }

  public function test_get_queries(){
    $query = new data_query();
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_queries')
        ->will($this->returnValue([$query, $query]));
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))->get_queries();
    $this->assertCount(2, $res);
  }

  public function test_update_description_1(){
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('set_description')
      ->with('Описание');
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue($query));
      $model->expects($this->once())
        ->method('update');
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))->update_description(5, 'Описание');
    $this->assertSame($query, $res);
  }

  public function test_update_description_2(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_query'] = function($p){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue(null));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->update_description(5, 'Описание');
  }

  public function test_update_reason_1(){
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('set_close_reason')
      ->with('Описание');
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue($query));
      $model->expects($this->once())
        ->method('update');
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))->update_reason(5, 'Описание');
    $this->assertSame($query, $res);
  }

  public function test_update_reason_2(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_query'] = function($p){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue(null));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->update_reason(5, 'Описание');
  }

  public function test_update_contact_information_1(){
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('set_contact_fio')
      ->with('Некрасов Евгений Валерьевич');
    $query->expects($this->once())
      ->method('set_contact_telephone')
      ->with(83439647957);
    $query->expects($this->once())
      ->method('set_contact_cellphone')
      ->with(89222944742);
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue($query));
      $model->expects($this->once())
        ->method('update');
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))
      ->update_contact_information(5, 'Некрасов Евгений Валерьевич',
      83439647957, 89222944742);
    $this->assertSame($query, $res);
  }

  public function test_update_contact_information_2(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_query'] = function($p){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue(null));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))
      ->update_contact_information(5, 'Некрасов Евгений Валерьевич',
      83439647957, 89222944742);
  }

  public function test_update_payment_status_1(){
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('set_payment_status')
      ->with('paid');
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue($query));
      $model->expects($this->once())
        ->method('update');
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))->update_payment_status(5, 'paid');
    $this->assertSame($query, $res);
  }

  public function test_update_payment_status_2(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_query'] = function($p){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue(null));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->update_payment_status(5, 'paid');
  }

  public function test_update_warning_status_1(){
    $query = $this->getMock('data_query');
    $query->expects($this->once())
      ->method('set_warning_status')
      ->with('normal');
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue($query));
      $model->expects($this->once())
        ->method('update');
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))->update_warning_status(5, 'normal');
    $this->assertSame($query, $res);
  }

  public function test_update_warning_status_2(){
    $this->setExpectedException('RuntimeException');
    $this->pimple['mapper_query'] = function($p){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue(null));
      return $model;
    };
    di::set_instance($this->pimple);
    (new model_query($this->company))->update_warning_status(5, 'normal');
  }

  public function test_update_work_type_1(){
    $query = $this->getMock('data_query');
    $type = new data_query_work_type();
    $query->expects($this->once())
      ->method('add_work_type')
      ->with($this->identicalTo($type));
    $this->pimple['mapper_query'] = function($p) use ($query){
      $model = $this->getMockBuilder('mapper_query')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('find')
        ->with(5)
        ->will($this->returnValue($query));
      $model->expects($this->once())
        ->method('update');
      return $model;
    };
    $this->pimple['model_query_work_type'] = function($p) use ($type){
      $model = $this->getMockBuilder('model_query_work_type')
        ->disableOriginalConstructor()
        ->getMock();
      $model->expects($this->once())
        ->method('get_query_work_type')
        ->with(7)
        ->will($this->returnValue($type));
      return $model;
    };
    di::set_instance($this->pimple);
    $res = (new model_query($this->company))->update_work_type(5, 7);
    $this->assertSame($query, $res);
  }
}