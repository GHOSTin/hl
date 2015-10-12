<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\numbers as controller;
use domain\house;
use domain\department;
use domain\street;
use domain\user;
use domain\event;
use domain\number;
use domain\number2event;

class main_controllers_numbers_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()->getMock();
    $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()->getMock();
    $this->request = new Request();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
    $this->app['em'] = $em;
  }

  public function test_accruals(){
    $this->request->query->set('id', 125);
    $this->app['user'] = 'user_object';
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\accruals.tpl',
                             [
                              'number' => 'number_object',
                              'user' => 'user_object'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->accruals($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_add_event(){
    $number = new number();
    $event = new event();
    $this->request->query->set('id', 125);
    $this->request->query->set('event', 253);
    $this->request->query->set('date', '21.12.1984');
    $this->app['em']->expects($this->exactly(2))
                    ->method('find')
                    ->withConsecutive(['domain\number', 125], ['domain\event', 253])
                    ->will($this->onConsecutiveCalls($number, $event));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\build_number_fio.tpl', [
                                                              'number' => $number,
                                                              'user' => 'user_object'
                                                            ])
                      ->will($this->returnValue('render_template'));
    $this->app['user'] = 'user_object';
    $response = $this->controller->add_event($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_edit_department(){
    $house = new house();
    $department = new department();
    $this->app['em']->expects($this->exactly(2))
                    ->method('find')
                    ->withConsecutive([$this->equalTo('\domain\house')], [$this->equalTo('\domain\department')])
                    ->will($this->onConsecutiveCalls($house, $department));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\build_house_content.tpl', ['house' => $house])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->edit_department($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page(){
    $model = $this->getMockBuilder('main\models\numbers')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('default_page')
          ->willReturn('render_template');
    $this->app['main\models\numbers'] = $model;
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_streets(){
    $model = $this->getMockBuilder('main\models\numbers')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('streets')
          ->willReturn(['streets_array']);
    $this->app['main\models\numbers'] = $model;
    $response = $this->controller->get_streets($this->app);
    $this->assertEquals($this->app->json(['streets_array']), $response);
  }


  public function test_exclude_event(){
    $number = new number();
    $n2e = new number2event();
    $n2e->set_number($number);
    $this->request->query->set('id', 125);
    $this->request->query->set('event', 253);
    $this->request->query->set('date', 1397562800);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByIndex'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByIndex')
               ->with(1397562800, 125, 253)
               ->will($this->returnValue([$n2e]));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\number2event')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('remove');
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['user'] = 'user_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\build_number_fio.tpl',
                             [
                              'number' => $number,
                              'user' => 'user_object'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->exclude_event($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_street_content(){
    $model = $this->getMockBuilder('main\models\numbers')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_street_content')
          ->with($this->isInstanceOf('main\models\street'))
          ->will($this->returnValue(['house_array']));
    $street_model = $this->getMockBuilder('main\models\street')
                  ->disableOriginalConstructor()
                  ->getMock();
    $factory = $this->getMockBuilder('main\models\factory')
                    ->disableOriginalConstructor()
                    ->getMock();
    $factory->expects($this->once())
            ->method('get_street_model')
            ->with(125)
            ->willReturn($street_model);
    $this->app['main\models\factory'] = $factory;
    $this->app['main\models\numbers'] = $model;
    $response = $this->controller->get_street_content($this->app, 125);
    $this->assertEquals($this->app->json(['house_array']), $response);
  }

  public function test_get_dialog_edit_department(){
    $this->request->query->set('house_id', 125);
    $this->app['em']->expects($this->once())
      ->method('find')
      ->with('\domain\house', 125)
      ->will($this->returnValue('house_object'));
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
      ->disableOriginalConstructor()
      ->getMock();
    $repository->expects($this->once())
      ->method('findAll')
      ->will($this->returnValue('departments_array'));
    $this->app['em']->expects($this->once())
      ->method('getRepository')
      ->with('\domain\department')
      ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_dialog_edit_department.tpl',
                             ['house' => 'house_object',
                              'departments' => 'departments_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_department($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_house_content(){
    $model = $this->getMockBuilder('main\models\numbers')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_house_content')
          ->with(125)
          ->willReturn(['render_template']);
    $this->app['main\models\numbers'] = $model;
    $response = $this->controller->get_house_content($this->app, 125);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }

  public function test_query_of_house(){
    $this->app['user'] = 'user_object';
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\house', 125)
                    ->will($this->returnValue('house_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\query_of_house.tpl',
                             ['user' => 'user_object',
                              'house' => 'house_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->query_of_house($this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_query_of_number(){
    $this->request->query->set('id', 125);
    $this->app['user'] = 'user_object';
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\query_of_number.tpl',
                             ['user' => 'user_object',
                              'number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->query_of_number($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_number_content(){
    $model = $this->getMockBuilder('main\models\numbers')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_number_content')
          ->with(125)
          ->willReturn(['render_template']);
    $this->app['main\models\numbers'] = $model;
    $response = $this->controller->get_number_content($this->app, 125);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }

  public function test_contact_info(){
    $this->request->query->set('id', 125);
    $this->app['user'] = 'user_object';
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\contact_info.tpl',
                             ['number' => 'number_object',
                              'user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->contact_info($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_add_event(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->will($this->returnValue('workgroup_array'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\workgroup')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_dialog_add_event.tpl',
                             [
                              'number' => 'number_object',
                              'workgroups' => 'workgroup_array'
                              ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_add_event($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_number(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 125)
                    ->will($this->returnValue('number_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_dialog_edit_number.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_number($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_events(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\workgroup', 125)
                    ->will($this->returnValue('workgroup_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_events.tpl', ['workgroup' => 'workgroup_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_events( $this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_exclude_event(){
    $this->request->query->set('id', 125);
    $this->request->query->set('event_id', 253);
    $this->request->query->set('time', 1397562800);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByIndex'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByIndex')
               ->with(1397562800, 125, 253)
               ->will($this->returnValue(['event']));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\number2event')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\get_dialog_exclude_event.tpl', ['n2e' => 'event'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_exclude_event($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_number_1(){
    $this->setExpectedException('RuntimeException');
    $this->request->query->set('id', 123);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 123)
                    ->will($this->returnValue(null));
    $response = $this->controller->update_number($this->request, $this->app);
  }

  public function test_update_number_2(){
    $this->request->query->set('id', 123);
    $this->request->query->set('number', 77755544);
    $number = $this->getMock('\domain\number');
    $number->expects($this->once())
           ->method('set_number')
           ->with(77755544);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 123)
                    ->will($this->returnValue($number));
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByNumber'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByNumber')
               ->with(77755544)
               ->will($this->returnValue(null));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\number')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['user'] = 'user_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('number\update_number_fio.tpl',
                             [
                              'number' => $number,
                              'user' => 'user_object'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_number($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_number_3(){
    $this->setExpectedException('RuntimeException');
    $this->request->query->set('id', 123);
    $this->request->query->set('number', 77755544);
    $number = $this->getMock('\domain\number');
    $number->expects($this->once())
           ->method('get_id')
           ->will($this->returnValue(123));
    $old_number = $this->getMock('\domain\number');
    $old_number->expects($this->once())
           ->method('get_id')
           ->will($this->returnValue(789));
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\number', 123)
                    ->will($this->returnValue($number));
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByNumber'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByNumber')
               ->with(77755544)
               ->will($this->returnValue($old_number));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\number')
                    ->will($this->returnValue($repository));
    $response = $this->controller->update_number($this->request, $this->app);
  }
}