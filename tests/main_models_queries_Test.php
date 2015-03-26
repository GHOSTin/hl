<?php

use main\models\queries as model;
use domain\query;

class model_queries_Test extends PHPUnit_Framework_TestCase{

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
                  ->with('query')
                  ->willReturn([]);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user])
                  ->getMock();
  }

  public function test_get_departments_1(){
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
    $model = new model($this->em, $this->session, $this->user);
    $this->assertEquals('departments_array', $model->get_departments());
  }

  public function test_get_departments_2(){
    $this->default_params['r_departments'] = [1, 3, 5];
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
    $model = new model($this->em, $this->session, $this->user);
    $this->assertEquals('departments_array', $model->get_departments());
  }

  public function test_get_filter_values_1(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = new model($this->em, $this->session, $this->user);
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
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = new model($this->em, $this->session, $this->user);
    $values = $model->get_filter_values();
    $this->assertNull($values['status']);
    $this->assertEquals(368, $values['department']);
    $this->assertEquals(288, $values['house']);
    $this->assertNull($values['work_type']);
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
    $model = new model($this->em, $this->session, $this->user);
    $this->assertEquals([10, 1, 7], $model->get_houses_by_street(125));
  }

  public function test_get_houses_by_street_2(){
    $this->default_params['r_departments'] = [2, 5];
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
    $model = new model($this->em, $this->session, $this->user);
    $this->assertEquals([10, 1, 7], $model->get_houses_by_street(125));
  }

  public function test_get_streets_1(){
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
               ->willReturn('streets_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\street')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user);
    $this->assertEquals('streets_array', $model->get_streets());
  }

  public function test_get_streets_2(){
    $this->default_params['r_departments'] = [2, 5];
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
               ->with(['department' => $this->default_params['r_departments']])
               ->willReturn([$house]);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\house')
             ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session, $this->user);
    $this->assertContainsOnlyInstancesOf('domain\street', $model->get_streets());
  }

  public function test_get_params(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = new model($this->em, $this->session, $this->user);
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
    $model = new model($this->em, $this->session, $this->user);
    $this->assertEquals('queries_array', $model->get_queries());
  }

  public function test_get_timeline(){
    $time = $this->default_params['time_begin'];
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = new model($this->em, $this->session, $this->user);
    $this->assertEquals(strtotime('noon', $time), $model->get_timeline($time));
  }

  public function test_init_default_params(){
    $params['departments'] = [9, 10, 11];
    $params['r_departments'] = [9, 10, 11];
    $params['houses'] = [];
    $params['status'] = query::$status_list;
    $params['streets'] = [];
    $params['work_types'] = [];
    $params['time_begin'] = strtotime('midnight');
    $params['time_end'] = strtotime('tomorrow');
    $profile = $this->getMockBuilder('domain\profile')
                    ->disableOriginalConstructor()
                    ->setMethods(['get_restrictions'])
                    ->getMock();
    $profile->expects($this->once())
            ->method('get_restrictions')
            ->willReturn(['departments' => [9, 10, 11]]);
    $this->user->expects($this->once())
               ->method('get_profile')
               ->willReturn($profile);
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user])
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
                  ->with('query')
                  ->willReturn($this->default_params);
    $this->session->expects($this->once())
                  ->method('set')
                  ->with('query', $params);
    $model = new model($this->em, $this->session, $this->user);
    $model->save_params($params);
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
                  ->setConstructorArgs([$this->em, $this->session, $this->user])
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
                  ->setConstructorArgs([$this->em, $this->session, $this->user])
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
                  ->setConstructorArgs([$this->em, $this->session, $this->user])
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
                  ->setConstructorArgs([$this->em, $this->session, $this->user])
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
                  ->setConstructorArgs([$this->em, $this->session, $this->user])
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
                  ->setConstructorArgs([$this->em, $this->session, $this->user])
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
                  ->setConstructorArgs([$this->em, $this->session, $this->user])
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
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['work_types' => []]);
    $model->set_work_type(0);
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
                  ->with('query')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\queries')
                  ->setConstructorArgs([$this->em, $this->session, $this->user])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['work_types' => [125]]);
    $model->set_work_type(125);
  }
}