<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\works as controller;
use domain\workgroup;
use domain\work;
use domain\event;

class controller_works_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()
                 ->getMock();
    $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()
               ->getMock();
    $this->request = new Request();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
    $this->app['em'] = $em;
  }

  public function test_add_event(){
    $this->request->query->set('workgroup_id', 125);
    $this->request->query->set('event_id', 253);
    $event = new event();
    $workgroup = $this->getMock('domain\workgroup');
    $workgroup->expects($this->once())
              ->method('add_event')
              ->with($event);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->exactly(2))
               ->method('findOneById')
               ->withConsecutive([125], [253])
               ->will($this->onConsecutiveCalls($workgroup, $event));
    $this->app['em']->expects($this->exactly(2))
                    ->method('getRepository')
                    ->withConsecutive(['domain\workgroup'], ['domain\event'])
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_workgroup_content.tpl', ['workgroup' => $workgroup])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->add_event($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_add_work(){
    $this->request->query->set('workgroup_id', 125);
    $this->request->query->set('work_id', 253);
    $work = new work();
    $workgroup = $this->getMock('\domain\workgroup');
    $workgroup->expects($this->once())
              ->method('add_work')
              ->with($work);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->exactly(2))
               ->method('findOneById')
               ->withConsecutive([125], [253])
               ->will($this->onConsecutiveCalls($workgroup, $work));
    $this->app['em']->expects($this->exactly(2))
                    ->method('getRepository')
                    ->withConsecutive(['\domain\workgroup'], ['\domain\work'])
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_workgroup_content.tpl', ['workgroup' => $workgroup])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->add_work($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_private_create_event_1(){
    $this->request->query->set('name', 'Привет');
    $this->setExpectedException('RuntimeException');
    $event = new event();
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByName'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByName')
               ->with('Привет')
               ->will($this->returnValue($event));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\event')
                    ->will($this->returnValue($repository));
    $this->controller->create_event($this->request, $this->app);
  }

  public function test_private_create_event_2(){
    $this->request->query->set('name', 'Привет');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByName', 'findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByName')
               ->with('Привет')
               ->will($this->returnValue(null));
    $repository->expects($this->once())
               ->method('findAll')
               ->will($this->returnValue('events_array'));
    $this->app['em']->expects($this->exactly(2))
                    ->method('getRepository')
                    ->with('domain\event')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('persist');
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\events.tpl', ['events' => 'events_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->create_event($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_private_create_work_1(){
    $this->request->query->set('name', 'Привет');
    $this->setExpectedException('RuntimeException');
    $work = new workgroup();
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByName'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByName')
               ->with('Привет')
               ->will($this->returnValue($work));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\work')
                    ->will($this->returnValue($repository));
    $this->controller->create_work($this->request, $this->app);
  }

  public function test_private_create_work_2(){
    $this->request->query->set('name', 'Привет');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByName', 'findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByName')
               ->with('Привет')
               ->will($this->returnValue(null));
    $repository->expects($this->once())
               ->method('findAll')
               ->will($this->returnValue('works_array'));
    $this->app['em']->expects($this->exactly(2))
                    ->method('getRepository')
                    ->with('\domain\work')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('persist');
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\works.tpl', ['works' => 'works_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->create_work($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_create_workgroup_1(){
    $this->request->query->set('name', 'Привет');
    $this->setExpectedException('RuntimeException');
    $workgroup = new workgroup();
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByName'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByName')
               ->with('Привет')
               ->will($this->returnValue($workgroup));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\workgroup')
                    ->will($this->returnValue($repository));
    $this->controller->create_workgroup($this->request, $this->app);
  }

  public function test_private_create_workgroup_2(){
    $this->request->query->set('name', 'Привет');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByName', 'findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByName')
               ->with('Привет')
               ->will($this->returnValue(null));
    $repository->expects($this->once())
               ->method('findAll')
               ->will($this->returnValue('workgroup_array'));
    $this->app['em']->expects($this->exactly(2))
                    ->method('getRepository')
                    ->with('\domain\workgroup')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('persist');
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\workgroups.tpl', ['workgroups' => 'workgroup_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->create_workgroup($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
   $repository->expects($this->once())
              ->method('findBy')
              ->with([], ['name' => 'ASC'])
              ->willReturn('wokgroup_array');
    $repository->expects($this->exactly(2))
               ->method('findAll')
               ->will($this->onConsecutiveCalls('work_array', 'event_array'));
    $this->app['em']->expects($this->exactly(3))
                    ->method('getRepository')
                    ->withConsecutive(['domain\workgroup'], ['domain\work'], ['domain\event'])
                    ->will($this->returnValue($repository));
    $this->app['user'] = 'user_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\default_page.tpl',
                             [
                              'user' => 'user_object',
                              'workgroups' => 'wokgroup_array',
                              'works' => 'work_array',
                              'events' => 'event_array'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_exclude_event(){
    $this->request->query->set('workgroup_id', 125);
    $this->request->query->set('event_id', 253);
    $workgroup = $this->getMock('domain\workgroup');
    $event = new event();
    $workgroup->expects($this->once())
              ->method('exclude_event')
              ->with($event);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->exactly(2))
               ->method('findOneById')
               ->withConsecutive([125], [253])
               ->will($this->onConsecutiveCalls($workgroup, $event));
    $this->app['em']->expects($this->exactly(2))
                    ->method('getRepository')
                    ->withConsecutive(['domain\workgroup'], ['domain\event'])
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_workgroup_content.tpl', ['workgroup' => $workgroup])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->exclude_event($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_exclude_work(){
    $this->request->query->set('workgroup_id', 125);
    $this->request->query->set('work_id', 253);
    $workgroup = $this->getMock('\domain\workgroup');
    $work = new work();
    $workgroup->expects($this->once())
              ->method('exclude_work')
              ->with($work);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->exactly(2))
               ->method('findOneById')
               ->withConsecutive([125], [253])
               ->will($this->onConsecutiveCalls($workgroup, $work));
    $this->app['em']->expects($this->exactly(2))
                    ->method('getRepository')
                    ->withConsecutive(['\domain\workgroup'], ['\domain\work'])
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_workgroup_content.tpl', ['workgroup' => $workgroup])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->exclude_work($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_event_content(){
    $this->request->query->set('id', 125);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneById')
               ->with(125)
               ->will($this->returnValue('event_object'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\event')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_event_content.tpl', ['event' => 'event_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_event_content($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_work_content(){
    $this->request->query->set('id', 125);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneById')
               ->with(125)
               ->will($this->returnValue('work_object'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\work')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_work_content.tpl', ['work' => 'work_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_work_content($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_workgroup_content(){
    $this->request->query->set('id', 125);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneById')
               ->with(125)
               ->will($this->returnValue('workgroup_object'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\workgroup')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_workgroup_content.tpl', ['workgroup' => 'workgroup_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_workgroup_content($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_add_event(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById', 'findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneById')
               ->will($this->returnValue('workgroup_object'));
    $repository->expects($this->once())
               ->method('findAll')
               ->will($this->returnValue('event_array'));
    $this->app['em']->expects($this->exactly(2))
                    ->method('getRepository')
                    ->withConsecutive(['domain\workgroup'], ['domain\event'])
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_dialog_add_event.tpl',
                             [
                              'workgroup' => 'workgroup_object',
                              'events' => 'event_array'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_add_event($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_add_work(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById', 'findAll'])
                       ->getMock();
    $repository->expects($this->once())
              ->method('findOneById')
              ->will($this->returnValue('workgroup_object'));
    $repository->expects($this->once())
               ->method('findAll')
               ->will($this->returnValue('work_array'));
    $this->app['em']->expects($this->exactly(2))
                   ->method('getRepository')
                   ->withConsecutive(['\domain\workgroup'], ['\domain\work'])
                   ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_dialog_add_work.tpl',
                             [
                              'workgroup' => 'workgroup_object',
                              'works' => 'work_array'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_add_work($this->request,  $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_create_event(){
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_dialog_create_event.tpl')
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_create_event($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_create_work(){
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_dialog_create_work.tpl')
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_create_work($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_create_workgroup(){
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_dialog_create_workgroup.tpl')
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_create_workgroup($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_exclude_event(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->exactly(2))
               ->method('findOneById')
               ->will($this->onConsecutiveCalls('workgroup_object', 'event_object'));
    $this->app['em']->expects($this->exactly(2))
                    ->method('getRepository')
                    ->withConsecutive(['domain\workgroup'], ['domain\event'])
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_dialog_exclude_event.tpl',
                             [
                              'workgroup' => 'workgroup_object',
                              'event' => 'event_object'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_exclude_event($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_exclude_work(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->exactly(2))
               ->method('findOneById')
               ->will($this->onConsecutiveCalls('workgroup_object', 'work_object'));
    $this->app['em']->expects($this->exactly(2))
                    ->method('getRepository')
                    ->withConsecutive(['\domain\workgroup'], ['\domain\work'])
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_dialog_exclude_work.tpl',
                             [
                              'workgroup' => 'workgroup_object',
                              'work' => 'work_object'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_exclude_work($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_rename_event(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneById')
               ->will($this->returnValue('event_object'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\event')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_dialog_rename_event.tpl', ['event' => 'event_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_rename_event($this->request,  $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_rename_work(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneById')
               ->will($this->returnValue('work_object'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\work')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_dialog_rename_work.tpl', ['work' => 'work_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_rename_work($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_rename_workgroup(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneById')
               ->will($this->returnValue('workgroup_object'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\workgroup')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\get_dialog_rename_workgroup.tpl', ['workgroup' => 'workgroup_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_rename_workgroup($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_rename_event(){
    $this->request->query->set('id', 125);
    $this->request->query->set('name', 'Привет');
    $work = $this->getMock('domain\event');
    $work->expects($this->once())
         ->method('set_name')
         ->with('Привет');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneById')
               ->with(125)
               ->will($this->returnValue($work));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\event')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $response = $this->controller->rename_event($this->request, $this->app);
  }

  public function test_rename_work(){
    $this->request->query->set('id', 125);
    $this->request->query->set('name', 'Привет');
    $work = $this->getMock('\domain\work');
    $work->expects($this->once())
         ->method('set_name')
         ->with('Привет');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneById')
               ->with(125)
               ->will($this->returnValue($work));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\work')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $response = $this->controller->rename_work($this->request, $this->app);
  }

  public function test_rename_workgroup(){
    $this->request->query->set('workgroup_id', 125);
    $this->request->query->set('name', 'Привет');
    $workgroup = $this->getMock('\domain\workgroup');
    $workgroup->expects($this->once())
              ->method('set_name')
              ->with('Привет');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneById'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneById')
               ->with(125)
               ->will($this->returnValue($workgroup));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\workgroup')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('works\rename_workgroup.tpl', ['workgroup' => $workgroup])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->rename_workgroup($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }
}