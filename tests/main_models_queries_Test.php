<?php

use main\models\queries as model;
use domain\query;
use domain\house;
use domain\number;
use domain\number_request;
use domain\flat;
use domain\workgroup;
use domain\query_type;
use domain\department;

class model_queries_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->session = $this->getMock('Symfony\Component\HttpFoundation\Session\Session');
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->time = time();
    $this->default_params = [
                             'status' => ['open'],
                             'time_begin' => $this->time,
                             'time_end' => $this->time,
                             'work_types' => [1],
                             'query_types' => [],
                             'houses' => [],
                             'streets' => [253],
                             'r_departments' => [],
                             'departments' => [],
                             'r_departments' => []
                            ];
  }


  public function test_abort_query_from_request_dialog(){
    $house = new house();
    $flat = new flat();
    $number = new number();
    $flat = flat::new_instance($house, '15');
    $number->set_flat($flat);
    $request = new number_request($number, 'Описание');
    $this->twig->expects($this->once())
               ->method('render')
               ->with('query\abort_query_from_request_dialog.tpl',
                      [
                        'query_work_types' => 'categories_array',
                        'queries' => 'queries_array',
                        'number' => $number,
                        'request' => $request,
                        'query_types' => 'query_types_array'
                      ])
               ->will($this->returnValue('render_template'));
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['get_request', 'get_categories', 'get_query_of_house', 'get_query_types'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_request')
          ->with('125', '1397562800')
          ->willReturn($request);
    $model->expects($this->once())
          ->method('get_query_of_house')
          ->with($house, 5)
          ->willReturn('queries_array');
    $model->expects($this->once())
          ->method('get_categories')
          ->willReturn('categories_array');
    $model->expects($this->once())
          ->method('get_query_types')
          ->willReturn('query_types_array');
    $response = $model->abort_query_from_request_dialog('125', '1397562800');
    $this->assertEquals('render_template', $response);
  }

  public function test_create_query_1(){
    $house = new house();
    $number = new number();
    $department = new department();
    $house->set_department($department);
    $flat = flat::new_instance($house, '15');
    $number->set_flat($flat);
    $query_type = new query_type();
    $category = new workgroup();
    $this->em->expects($this->exactly(3))
             ->method('find')
             ->withConsecutive(['domain\query_type', '129'], ['domain\workgroup', '127'], ['domain\number', '125'])
             ->will($this->onConsecutiveCalls($query_type, $category, $number));
    $this->em->expects($this->once())
             ->method('persist')
             ->with($this->isInstanceOf('domain\query'));
    $this->em->expects($this->once())
             ->method('flush');
    $this->twig->expects($this->once())
               ->method('render')
               ->with('query\query_titles.tpl', ['queries' => 'queries_array'])
               ->will($this->returnValue('render_template'));
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['generate_next_number', 'get_today_queries'])
                  ->getMock();
    $model->expects($this->once())
          ->method('generate_next_number')
          ->willReturn('123222');
    $model->expects($this->once())
          ->method('get_today_queries')
          ->willReturn('queries_array');
    $response = $model->create_query('Описание', 'number', '127', '129', 'ФИО', '647957', '+79222944742', '125');
    $this->assertEquals('render_template', $response);
  }

  public function test_create_query_2(){
    $house = new house();
    $number = new number();
    $department = new department();
    $house->set_department($department);
    $flat = flat::new_instance($house, '15');
    $number->set_flat($flat);
    $query_type = new query_type();
    $category = new workgroup();
    $this->em->expects($this->exactly(3))
             ->method('find')
             ->withConsecutive(['domain\query_type', '129'], ['domain\workgroup', '127'], ['domain\house', '125'])
             ->will($this->onConsecutiveCalls($query_type, $category, $house));
    $this->em->expects($this->once())
             ->method('persist')
             ->with($this->isInstanceOf('domain\query'));
    $this->em->expects($this->once())
             ->method('flush');
    $this->twig->expects($this->once())
               ->method('render')
               ->with('query\query_titles.tpl', ['queries' => 'queries_array'])
               ->will($this->returnValue('render_template'));
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['generate_next_number', 'get_today_queries'])
                  ->getMock();
    $model->expects($this->once())
          ->method('generate_next_number')
          ->willReturn('123222');
    $model->expects($this->once())
          ->method('get_today_queries')
          ->willReturn('queries_array');
    $response = $model->create_query('Описание', 'house', '127', '129', 'ФИО', '647957', '+79222944742', '125');
    $this->assertEquals('render_template', $response);
  }

  public function test_create_query_from_request(){
    $house = new house();
    $number = new number();
    $department = new department();
    $house->set_department($department);
    $flat = flat::new_instance($house, '15');
    $number->set_flat($flat);
    $query_type = new query_type();
    $category = new workgroup();
    $request = new number_request($number, 'Описание');
    $this->em->expects($this->exactly(2))
             ->method('find')
             ->withConsecutive(['domain\query_type', '129'], ['domain\workgroup', '127'])
             ->will($this->onConsecutiveCalls($query_type, $category));
    $this->em->expects($this->once())
             ->method('persist')
             ->with($this->isInstanceOf('domain\query'));
    $this->em->expects($this->once())
             ->method('flush');
    $this->twig->expects($this->once())
               ->method('render')
               ->with('query\query_titles.tpl', ['queries' => 'queries_array'])
               ->will($this->returnValue('render_template'));
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['get_request', 'generate_next_number', 'get_today_queries'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_request')
          ->with('125', '1397562800')
          ->willReturn($request);
    $model->expects($this->once())
          ->method('generate_next_number')
          ->willReturn('123222');
    $model->expects($this->once())
          ->method('get_today_queries')
          ->willReturn('queries_array');
    $response = $model->create_query_from_request('Описание', '1397562800', '127', '129', '125');
    $this->assertEquals('render_template', $response);
  }

  public function test_abort_query_from_request(){
    $house = new house();
    $number = new number();
    $department = new department();
    $house->set_department($department);
    $flat = flat::new_instance($house, '15');
    $number->set_flat($flat);
    $query_type = new query_type();
    $category = new workgroup();
    $request = new number_request($number, 'Описание');
    $this->em->expects($this->exactly(2))
             ->method('find')
             ->withConsecutive(['domain\query_type', '129'], ['domain\workgroup', '127'])
             ->will($this->onConsecutiveCalls($query_type, $category));
    $this->em->expects($this->once())
             ->method('persist')
             ->with($this->isInstanceOf('domain\query'));
    $this->em->expects($this->once())
             ->method('flush');
    $this->twig->expects($this->once())
               ->method('render')
               ->with('query\query_titles.tpl', ['queries' => 'queries_array'])
               ->will($this->returnValue('render_template'));
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['get_request', 'generate_next_number', 'get_today_queries'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_request')
          ->with('125', '1397562800')
          ->willReturn($request);
    $model->expects($this->once())
          ->method('generate_next_number')
          ->willReturn('123222');
    $model->expects($this->once())
          ->method('get_today_queries')
          ->willReturn('queries_array');
    $response = $model->abort_query_from_request('Описание', '1397562800', '127', '129', '125');
    $this->assertEquals('render_template', $response);
  }

  public function test_create_query_from_request_dialog(){
    $house = new house();
    $number = new number();
    $flat = flat::new_instance($house, '15');
    $number->set_flat($flat);
    $request = new number_request($number, 'Описание');
    $this->twig->expects($this->once())
               ->method('render')
               ->with('query\create_query_from_request_dialog.tpl',
                      [
                        'query_work_types' => 'categories_array',
                        'queries' => 'queries_array',
                        'number' => $number,
                        'request' => $request,
                        'query_types' => 'query_types_array'
                      ])
               ->will($this->returnValue('render_template'));
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['get_request', 'get_categories', 'get_query_of_house', 'get_query_types'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_request')
          ->with('125', '1397562800')
          ->willReturn($request);
    $model->expects($this->once())
          ->method('get_query_of_house')
          ->with($house, 5)
          ->willReturn('queries_array');
    $model->expects($this->once())
          ->method('get_categories')
          ->willReturn('categories_array');
    $model->expects($this->once())
          ->method('get_query_types')
          ->willReturn('query_types_array');
    $response = $model->create_query_from_request_dialog('125', '1397562800');
    $this->assertEquals('render_template', $response);
  }

  public function test_phrases(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 125)
             ->willReturn('workgroup_object');
    $this->twig->expects($this->once())
               ->method('render')
               ->with('query\phrases.tpl', ['workgroup' => 'workgroup_object'])
               ->will($this->returnValue('render_template'));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals('render_template', $model->phrases(125));
  }

  public function test_get_categories_1(){
    $this->user->expects($this->once())
               ->method('get_restriction')
               ->with('categories')
               ->willReturn([]);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->with(['name' => 'ASC'])
               ->willReturn('categories_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\workgroup')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals('categories_array', $model->get_categories());
  }

  public function test_get_categories_2(){
    $this->user->expects($this->once())
               ->method('get_restriction')
               ->with('categories')
               ->willReturn([1, 3, 5]);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByid'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByid')
               ->with([1, 3, 5], ['name' => 'ASC'])
               ->willReturn('categories_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\workgroup')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals('categories_array', $model->get_categories());
  }

  public function test_constructor(){
    $this->session->expects($this->once())
                  ->method('get')
                  ->with('query')
                  ->willReturn([]);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->getMock();
  }

  public function test_outages(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['active'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('active')
               ->willReturn('outages_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\outage')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('query/outages.tpl', ['outages' => 'outages_array'])
               ->willReturn('render_template');
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals(['outages' => 'render_template'], $model->outages());
  }

  public function test_get_day_stats(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByParams'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByParams')
               ->with($this->default_params)
               ->willReturn([]);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\query')
             ->will($this->returnValue($repository));
    $res['sum'] = 0;
    $res['open'] = 0;
    $res['working'] = 0;
    $res['close'] = 0;
    $res['reopen'] = 0;
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals($res, $model->get_day_stats());
  }

  public function test_get_departments_1(){
    $this->user->expects($this->once())
               ->method('get_restriction')
               ->with('departments')
               ->willReturn([]);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->with(['name' => 'ASC'])
               ->willReturn('departments_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\department')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals('departments_array', $model->get_departments());
  }

  public function test_get_departments_2(){
    $this->user->expects($this->once())
               ->method('get_restriction')
               ->with('departments')
               ->willReturn([1, 3, 5]);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByid'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByid')
               ->with([1, 3, 5], ['name' => 'ASC'])
               ->willReturn('departments_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\department')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals('departments_array', $model->get_departments());
  }

  public function test_get_filter_values_1(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $values = $model->get_filter_values();
    $this->assertEquals('open', $values['status']);
    $this->assertNull($values['department']);
    $this->assertNull($values['house']);
    $this->assertEquals(1, $values['work_type']);
    $this->assertEquals(253, $values['streets']);
  }

  public function test_get_filter_values_2(){
    $this->default_params['departments'] = [368];
    $this->default_params['houses'] = [288];
    $this->default_params['status'] = query::$status_list;
    $this->default_params['streets'] = [];
    $this->default_params['work_types'] = [];
    $this->default_params['query_types'] = [];
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $values = $model->get_filter_values();
    $this->assertNull($values['status']);
    $this->assertEquals(368, $values['department']);
    $this->assertEquals(288, $values['house']);
    $this->assertNull($values['work_type']);
    $this->assertNull($values['query_types']);
    $this->assertNull($values['streets']);
  }

  public function test_get_houses_by_street_1(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByStreet'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByStreet')
               ->with(125)
               ->willReturn([10, 1, 7]);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\house')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals([10, 1, 7], $model->get_houses_by_street(125));
  }

  public function test_get_houses_by_street_2(){
    $this->user->expects($this->once())
               ->method('get_restriction')
               ->with('departments')
               ->willReturn([2, 5]);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findBy'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with(['department' => [2, 5], 'street' => 125])
               ->willReturn([10, 1, 7]);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\house')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals([10, 1, 7], $model->get_houses_by_street(125));
  }

  public function test_get_query_types(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->with(['name' => 'ASC'])
               ->willReturn('query_types_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\query_type')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals('query_types_array', $model->get_query_types());
  }

  public function test_get_query_of_house(){
    $house = new house();
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByHouse'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByHouse')
               ->with($house,['id' => 'DESC'], 5)
               ->willReturn('queries_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\query')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals('queries_array', $model->get_query_of_house($house, 5));
  }

  public function test_get_request(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneBy'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneBy')
               ->with([
                      'number' => '125',
                      'time' => '1397562800'
                     ])
               ->willReturn('request');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\number_request')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals('request', $model->get_request('125', '1397562800'));
  }

  public function test_get_streets_1(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findBy'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with([], ['name' => 'ASC'])
               ->willReturn('streets_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\street')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals('streets_array', $model->get_streets());
  }

  public function test_get_streets_2(){
    $this->user->expects($this->once())
               ->method('get_restriction')
               ->with('departments')
               ->willReturn([2, 5]);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $street = $this->getMock('domain\street');
    $street->method('get_id')
           ->willReturn(789);
    $house = $this->getMock('domain\house');
    $house->method('get_street')
          ->willReturn($street);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findBy'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with(['department' => [2, 5]])
               ->willReturn([$house]);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\house')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertContainsOnlyInstancesOf('domain\street', $model->get_streets());
  }

  public function test_get_params(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals($this->default_params, $model->get_params());
  }

  public function test_get_queries(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByParams'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByParams')
               ->with($this->default_params)
               ->willReturn('queries_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\query')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals('queries_array', $model->get_queries());
  }

  public function test_get_timeline(){
    $time = $this->default_params['time_begin'];
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals(strtotime('noon', $time), $model->get_timeline($time));
  }

  public function test_get_today_queries(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByParams'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByParams')
               ->with([
                       'time_begin' => strtotime('midnight'),
                       'time_end' => strtotime('tomorrow'),
                       'status' => query::$status_list
                      ])
               ->willReturn('queries_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\query')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals('queries_array', $model->get_today_queries());
  }

  public function test_init_default_params(){
    $params['departments'] = [9, 10, 11];
    $params['houses'] = [];
    $params['status'] = query::$status_list;
    $params['streets'] = [];
    $params['work_types'] = [7, 10, 12];
    $params['query_types'] = [];
    $params['time_begin'] = strtotime('midnight');
    $params['time_end'] = strtotime('tomorrow');
    $this->user->expects($this->exactly(2))
               ->method('get_restriction')
               ->withConsecutive(['departments'], ['categories'])
               ->will($this->onConsecutiveCalls([9, 10, 11], [7, 10, 12]));
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with($params);
    $model->init_default_params();
  }

  public function test_noclose(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('query\query_titles.tpl', ['queries' => []])
               ->will($this->returnValue('render_template'));
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByParams'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByParams')
               ->willReturn([]);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\query')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $model->noclose(1122);
  }

  public function test_save_params(){
    $params = $this->default_params;
    $params['time_begin'] = 12;
    $params['time_begin'] = 24;
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $this->session->expects($this->once())
                  ->method('set')
                  ->with('query', $params);
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $model->save_params($params);
  }

  public function test_selections(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('query\selections.tpl')
               ->will($this->returnValue('render_template'));
    $model = new model($this->em, $this->session, $this->user, $this->twig);
    $this->assertEquals('render_template', $model->selections());
  }

  public function test_set_department_1(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\department', 0)
             ->willReturn(null);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $this->user->expects($this->once())
                  ->method('get_restriction')
                  ->with('departments')
                  ->willReturn([]);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with([
                  'departments' => [],
                  'streets' => [],
                  'houses' => []
                 ]);
    $model->set_department(0);
  }

  public function test_set_department_2(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\department', 0)
             ->willReturn(null);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $this->user->expects($this->once())
                  ->method('get_restriction')
                  ->with('departments')
                  ->willReturn([1, 2]);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with([
                  'departments' => [1, 2],
                  'streets' => [],
                  'houses' => []
                 ]);
    $model->set_department(0);
  }

  public function test_set_department_3(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\department', 125)
             ->willReturn('department_object');
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $this->user->expects($this->once())
                  ->method('get_restriction')
                  ->with('departments')
                  ->willReturn([]);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with([
                  'departments' => [125],
                  'streets' => [],
                  'houses' => []
                 ]);
    $model->set_department(125);
  }

  public function test_set_department_4(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\department', 125)
             ->willReturn('department_object');
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $this->user->expects($this->once())
                  ->method('get_restriction')
                  ->with('departments')
                  ->willReturn([1, 2]);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with([
                  'departments' => [1, 2],
                  'streets' => [],
                  'houses' => []
                 ]);
    $model->set_department(125);
  }

  public function test_set_house_1(){
    $house = $this->getMock('domain\house');
    $house->expects($this->once())
          ->method('get_id')
          ->will($this->returnValue(125));
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\house', 125)
             ->willReturn($house);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['houses' => [125]]);
    $model->set_house(125);
  }

   public function test_set_house_2(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\house', 0)
             ->willReturn(null);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['set_street'])
                  ->getMock();
    $model->expects($this->once())
          ->method('set_street')
          ->with(253);
    $model->set_house(0);
  }

  public function test_set_status_1(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['status' => ['open']]);
    $model->set_status('open');
  }

  public function test_set_status_2(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['status' => query::$status_list]);
    $model->set_status('wrong_status');
  }

   public function test_set_street_1(){
    $street = $this->getMock('domain\street');
    $street->expects($this->once())
           ->method('get_id')
           ->willReturn(200);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['get_streets', 'save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_streets')
          ->willReturn([$street]);
    $model->expects($this->once())
          ->method('save_params')
          ->with([
                  'departments' => [],
                  'streets' => [],
                  'houses' => []
                 ]);
    $model->set_street(125);
  }

  public function test_set_street_2(){
    $street = $this->getMock('domain\street');
    $street->expects($this->once())
           ->method('get_id')
           ->willReturn(125);
    $house = $this->getMock('domain\house');
    $house->expects($this->once())
          ->method('get_id')
          ->willReturn(300);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['get_streets', 'save_params', 'get_houses_by_street'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_streets')
          ->willReturn([$street]);
    $model->expects($this->once())
          ->method('get_houses_by_street')
          ->with(125)
          ->willReturn([$house]);
    $model->expects($this->once())
          ->method('save_params')
          ->with([
                  'departments' => [],
                  'streets' => [125],
                  'houses' => [300]
                 ]);
    $model->set_street(125);
  }

  public function test_set_time(){
    $time = time();
    $params['time_begin'] = strtotime('midnight', $time);
    $params['time_end'] = strtotime('tomorrow', $time);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with($params);
    $model->set_time($time);
  }

  public function test_set_worktype_1(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 0)
             ->willReturn(null);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $this->user->expects($this->once())
                  ->method('get_restriction')
                  ->with('categories')
                  ->willReturn([]);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['work_types' => []]);
    $model->set_work_type(0);
  }

  public function test_set_worktype_2(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 0)
             ->willReturn(null);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $this->user->expects($this->once())
                  ->method('get_restriction')
                  ->with('categories')
                  ->willReturn([1, 2]);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['work_types' => [1, 2]]);
    $model->set_work_type(0);
  }

  public function test_set_worktype_3(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 125)
             ->willReturn('workgroup_object');
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $this->user->expects($this->once())
                  ->method('get_restriction')
                  ->with('categories')
                  ->willReturn([]);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['work_types' => [125]]);
    $model->set_work_type(125);
  }

  public function test_set_worktype_4(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 125)
             ->willReturn('workgroup_object');
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $this->user->expects($this->once())
                  ->method('get_restriction')
                  ->with('categories')
                  ->willReturn([1, 2]);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['work_types' => [1, 2]]);
    $model->set_work_type(125);
  }

  public function test_set_query_type_1(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\query_type', 0)
             ->willReturn(null);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['query_types' => []]);
    $model->set_query_type(0);
  }

  public function test_set_query_type_2(){
    $query_type = $this->getMock('domain\query_type');
    $query_type->expects($this->once())
               ->method('get_id')
               ->will($this->returnValue(125));
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\query_type', 125)
             ->willReturn($query_type);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user, $this->twig])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['query_types' => [125]]);
    $model->set_query_type(125);
  }
}