<?php

use main\models\report_query as model;
use domain\query;

class main_models_report_queries_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->session = $this->getMock('Symfony\Component\HttpFoundation\Session\Session');
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->time = time();
    $this->default_params = [
                             'status' => ['open'],
                             'time_begin' => $this->time,
                             'time_end' => $this->time,
                             'work_types' => [1],
                             'houses' => [],
                             'streets' => [253],
                             'r_departments' => [],
                             'departments' => [],
                             'r_departments' => []
                            ];
  }

  public function test_constructor(){
    $this->session->expects($this->once())
                  ->method('get')
                  ->with('report_query')
                  ->willReturn([]);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
                  ->getMock();
  }

 public function test_get_filters_1(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = new model($this->em, $this->session);
    $values = $model->get_filters();
    $this->assertEquals('open', $values['status']);
    $this->assertEquals([], $values['department']);
    $this->assertNull($values['house']);
    $this->assertEquals(1, $values['work_type']);
    $this->assertEquals(253, $values['street']);
  }

  public function test_get_filter_values_2(){
    $this->default_params['departments'] = [368];
    $this->default_params['houses'] = [288];
    $this->default_params['status'] = query::$status_list;
    $this->default_params['streets'] = [];
    $this->default_params['work_types'] = [];
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = new model($this->em, $this->session);
    $values = $model->get_filters();
    $this->assertNull($values['status']);
    $this->assertEquals([368], $values['department']);
    $this->assertEquals(288, $values['house']);
    $this->assertNull($values['work_type']);
    $this->assertNull($values['street']);
  }

  public function test_get_queries(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_query')
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
    $model = new model($this->em, $this->session);
    $this->assertEquals('queries_array', $model->get_queries());
  }

  public function test_init_default_params(){
    $params['departments'] = [];
    $params['houses'] = [];
    $params['status'] = query::$status_list;
    $params['streets'] = [];
    $params['work_types'] = [];
    $params['time_begin'] = strtotime('midnight');
    $params['time_end'] = strtotime('tomorrow');
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with($params);
    $model->init_default_params();
  }

  public function test_save_params(){
    $params = $this->default_params;
    $params['time_begin'] = 12;
    $params['time_begin'] = 24;
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $this->session->expects($this->once())
                  ->method('set')
                  ->with('report_query', $params);
    $model = new model($this->em, $this->session);
    $model->save_params($params);
  }

  public function test_set_department_1(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\department', 0)
             ->willReturn(null);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
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
    $department = $this->getMock('domain\department');
    $department->expects($this->once())
               ->method('get_id')
               ->will($this->returnValue(125));
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\department', 125)
             ->willReturn($department);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
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
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
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
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
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
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
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
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
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
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->willReturn([$street]);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\street')
             ->will($this->returnValue($repository));
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
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
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $repository1 = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                        ->disableOriginalConstructor()
                        ->setMethods(['findAll'])
                        ->getMock();
    $repository1->expects($this->once())
                ->method('findAll')
                ->willReturn([$street]);
    $repository2 = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                        ->disableOriginalConstructor()
                        ->setMethods(['findByStreet'])
                        ->getMock();
    $repository2->expects($this->once())
                ->method('findByStreet')
                ->with(125)
                ->willReturn([$house]);
    $this->em->expects($this->exactly(2))
             ->method('getRepository')
             ->withConsecutive(['domain\street'], ['domain\house'])
             ->will($this->onConsecutiveCalls($repository1, $repository2));
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
                  ->setMethods(['get_streets', 'save_params', 'get_houses_by_street'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with([
                  'departments' => [],
                  'streets' => [125],
                  'houses' => [300]
                 ]);
    $model->set_street(125);
  }

  public function test_set_time_begin(){
    $time = time();
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['time_begin' => $time]);
    $model->set_time_begin($time);
  }

  public function test_set_time_end(){
    $time = time();
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['time_end' => $time]);
    $model->set_time_end($time);
  }

  public function test_set_worktype_1(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 0)
             ->willReturn(null);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['work_types' => []]);
    $model->set_worktype(0);
  }

  public function test_set_worktype_2(){
    $workgroup = $this->getMock('domain\workgroup');
    $workgroup->expects($this->once())
              ->method('get_id')
              ->will($this->returnValue(125));
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 125)
             ->willReturn($workgroup);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->setConstructorArgs([$this->em, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['work_types' => [125]]);
    $model->set_worktype(125);
  }
}