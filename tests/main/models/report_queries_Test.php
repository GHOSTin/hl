<?php namespace tests\main\models;

use Silex\Application;
use main\models\report_queries as model;
use domain\query;
use PHPUnit_Framework_TestCase;

class report_queries_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
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
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('reports/general_access')
               ->willReturn(true);
    $this->session = $this->getMock('Symfony\Component\HttpFoundation\Session\Session');
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_query')
                  ->willReturn($this->default_params);
    $this->app = new Application();
  }

  public function test_clear_filters(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('report\clear_filter_query.tpl', ['filters' => 'filters_array']);
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['init_default_params', 'get_filters'])
                  ->getMock();
    $model->expects($this->once())
          ->method('init_default_params');
    $model->expects($this->once())
          ->method('get_filters')
          ->willReturn('filters_array');
    $model->clear_filters();
  }

  public function test_get_categories_1(){
    $this->user->expects($this->once())
               ->method('get_restriction')
               ->with('categories')
               ->willReturn([]);
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
    $model = new model($this->twig, $this->em, $this->user, $this->session);
    $this->assertEquals('categories_array', $model->get_categories());
  }

  public function test_get_categories_2(){
    $this->user->expects($this->once())
               ->method('get_restriction')
               ->with('categories')
               ->willReturn([1, 3, 5]);
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
    $model = new model($this->twig, $this->em, $this->user, $this->session);
    $this->assertEquals('categories_array', $model->get_categories());
  }

  public function test_get_departments_1(){
    $this->user->expects($this->once())
               ->method('get_restriction')
               ->with('departments')
               ->willReturn([]);
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
    $model = new model($this->twig, $this->em, $this->user, $this->session);
    $this->assertEquals('departments_array', $model->get_departments());
  }

  public function test_get_departments_2(){
    $this->user->expects($this->once())
               ->method('get_restriction')
               ->with('departments')
               ->willReturn([1, 3, 5]);
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
    $model = new model($this->twig, $this->em, $this->user, $this->session);
    $this->assertEquals('departments_array', $model->get_departments());
  }

  public function test_get_filters_1(){
    $model = new model($this->twig, $this->em, $this->user, $this->session);
    $values = $model->get_filters();
    $this->assertEquals('open', $values['status']);
    $this->assertEquals([], $values['department']);
    $this->assertNull($values['house']);
    $this->assertEquals(1, $values['work_type']);
    $this->assertEquals(253, $values['street']);
  }

  public function test_get_streets_1(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->with(['name' => 'ASC'])
               ->willReturn('streets_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\street')
             ->will($this->returnValue($repository));
    $model = new model($this->twig, $this->em, $this->user, $this->session);
    $this->assertEquals('streets_array', $model->get_streets());
  }

  public function test_get_streets_2(){
    $this->user->expects($this->once())
               ->method('get_restriction')
               ->with('departments')
               ->willReturn([2, 5]);
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
    $model = new model($this->twig, $this->em, $this->user, $this->session);
    $this->assertContainsOnlyInstancesOf('domain\street', $model->get_streets());
  }

  public function test_report1(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByParams', 'findBy'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByParams')
               ->with($this->default_params)
               ->willReturn(['queries_array']);
    $repository->expects($this->once())
               ->method('findBy')
               ->willReturn(['workgroups_array']);

    $this->em->expects($this->exactly(2))
             ->method('getRepository')
             ->withConsecutive(['domain\query'], ['domain\workgroup'])
             ->will($this->returnValue($repository));
    $this->twig->expects($this->once())
               ->method('render')
               ->with('report\report_query_one.tpl');
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['get_stats'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_stats');
    $model->report1();
  }

  public function test_report1_xls(){
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
    $this->twig->expects($this->once())
               ->method('render')
               ->with('report\report_query_one_xls.tpl', ['queries' => 'queries_array']);
    $model = new model($this->twig, $this->em, $this->user, $this->session);
    $model->report1_xls();
  }

  public function test_init_default_params(){
    $params['departments'] = [];
    $params['houses'] = [];
    $params['status'] = query::$status_list;
    $params['streets'] = [];
    $params['work_types'] = [];
    $params['query_types'] = [];
    $params['time_begin'] = strtotime('midnight');
    $params['time_end'] = strtotime('tomorrow');
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
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
    $this->session->expects($this->once())
                  ->method('set')
                  ->with('report_query', $params);
    $model = new model($this->twig, $this->em, $this->user, $this->session);
    $model->save_params($params);
  }

  public function test_set_department_1(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\department', 0)
             ->willReturn(null);
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with([
                  'departments' => [],
                  'streets' => [],
                  'houses' => []
                 ]);
    $response = $model->set_department(0);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
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
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with([
                  'departments' => [125],
                  'streets' => [],
                  'houses' => []
                 ]);
    $response = $model->set_department(125);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
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
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['houses' => [125]]);
    $response = $model->set_house(125);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_set_house_2(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\house', 0)
             ->willReturn(null);
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['save_params', 'set_street'])
                  ->getMock();
    $model->expects($this->once())
          ->method('set_street')
          ->with(253);
    $response = $model->set_house(0);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_set_query_type_1(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\query_type', 0)
             ->willReturn(null);
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['query_types' => []]);
    $response = $model->set_query_type(0);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
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
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['query_types' => [125]]);
    $response = $model->set_query_type(125);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_set_status_1(){
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['status' => ['open']]);
    $response = $model->set_status('open');
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_set_status_2(){
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['status' => query::$status_list]);
    $response = $model->set_status('wrong_status');
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

   public function test_set_street_1(){
    $street = $this->getMock('domain\street');
    $street->expects($this->once())
           ->method('get_id')
           ->willReturn(200);
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
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
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
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
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
    $time = strtotime('21.12.1984');
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['time_begin' => $time]);
    $response = $model->set_time_begin('21.12.1984');
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_set_time_end(){
    $time = strtotime('21.12.1984');
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['time_end' => $time + 86400]);
    $response = $model->set_time_end('21.12.1984');
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_set_worktype_1(){
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\workgroup', 0)
             ->willReturn(null);
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['work_types' => []]);
    $response = $model->set_worktype(0);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
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
    $model = $this->getMockBuilder('main\models\report_queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['work_types' => [125]]);
    $response = $model->set_worktype(125);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }
}