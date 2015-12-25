<?php namespace tests\main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\users as controller;
use domain\user;
use PHPUnit_Framework_TestCase;

class users_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('Twig_Environment')
                 ->disableOriginalConstructor()
                 ->getMock();
    $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()
               ->getMock();
    $this->request = new Request();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
    $this->app['em'] = $em;
  }

  public function test_add_user(){
    $user = new user();
    $this->request->query->set('user_id', 125);
    $this->request->query->set('group_id', 253);
    $group = $this->getMock('domain\group');
    $group->expects($this->once())
          ->method('add_user')
          ->with($user);
    $this->app['em']->expects($this->exactly(2))
                    ->method('find')
                    ->withConsecutive(
                                       ['domain\group', 253],
                                       ['domain\user', 125]
                                     )
                    ->will($this->onConsecutiveCalls($group, $user));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\add_user.tpl', ['group' => $group])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->add_user($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_access(){
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn('user_object');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\access.tpl', ['user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->access($this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_exclude_user(){
    $user = new user();
    $this->request->query->set('user_id', 125);
    $this->request->query->set('group_id', 253);
    $group = $this->getMock('domain\group');
    $group->expects($this->once())
          ->method('exclude_user')
          ->with($user);
    $this->app['em']->expects($this->exactly(2))
                    ->method('find')
                    ->withConsecutive(
                                       ['domain\group', 253],
                                       ['domain\user', 125]
                                     )
                    ->will($this->onConsecutiveCalls($group, $user));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user/add_user.tpl', ['group' => $group])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->exclude_user($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_add_user(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\group', 125)
                    ->willReturn('group_object');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->willReturn('users_array');
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\user')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_dialog_add_user.tpl',
                             [
                              'group' => 'group_object',
                              'users' => 'users_array'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_add_user($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_create_group(){
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_dialog_create_group.tpl')
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_create_group($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_create_user(){
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_dialog_create_user.tpl')
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_create_user($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_fio(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn('user_object');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_dialog_edit_fio.tpl', ['user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_fio($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_group_name(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\group', 125)
                    ->willReturn('group_object');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_dialog_edit_group_name.tpl', ['group' => 'group_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_group_name($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_login(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn('user_object');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_dialog_edit_login.tpl', ['user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_login($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_user_status(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn('user_object');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_dialog_edit_user_status.tpl', ['user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_user_status($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_password(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn('user_object');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_dialog_edit_password.tpl', ['user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_password($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_exclude_user(){
    $this->request->query->set('user_id', 125);
    $this->request->query->set('group_id', 253);
    $this->app['em']->expects($this->exactly(2))
                    ->method('find')
                    ->withConsecutive(
                                       ['domain\user', 125],
                                       ['domain\group', 253]
                                     )
                    ->will($this->onConsecutiveCalls('user_object', 'group_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_dialog_exclude_user.tpl',
                            [
                             'user' => 'user_object',
                             'group' => 'group_object'
                            ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_exclude_user($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_group_content(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\group', 125)
                    ->willReturn('group_object');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_group_content.tpl', ['group' => 'group_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_group_content($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_group_profile(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\group', 125)
                    ->willReturn('group_object');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_group_profile.tpl', ['group' => 'group_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_group_profile($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_group_users(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\group', 125)
                    ->willReturn('group_object');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_group_users.tpl', ['group' => 'group_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_group_users($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_user_content(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn('user_object');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_user_content.tpl', ['user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_user_content($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_user_information(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn('user_object');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_user_information.tpl', ['user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_user_information($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_restrictions(){
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn('user_object');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findAll'])
                       ->getMock();
    $repository->expects($this->exactly(2))
              ->method('findAll')
              ->with(['name' => 'ASC'])
              ->will($this->onConsecutiveCalls('departments_array', 'workgroups_array'));
    $this->app['em']->expects($this->exactly(2))
                    ->method('getRepository')
                    ->withConsecutive(['domain\department'], ['domain\workgroup'])
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\get_restrictions.tpl',
                            [
                             'user' => 'user_object',
                             'departments' => 'departments_array',
                             'categories' => 'workgroups_array'
                            ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_restrictions($this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_fio(){
    $this->request->query->set('id', 125);
    $this->request->query->set('lastname', 'Некрасов');
    $this->request->query->set('firstname', 'Евгений');
    $this->request->query->set('middlename', 'Валерьевич');
    $user = $this->getMock('domain\user');
    $user->expects($this->once())
         ->method('set_lastname')
         ->with('Некрасов');
    $user->expects($this->once())
         ->method('set_firstname')
         ->with('Евгений');
    $user->expects($this->once())
         ->method('set_middlename')
         ->with('Валерьевич');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn($user);
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\update_fio.tpl', ['user' => $user])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_fio($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_group_name(){
    $this->request->query->set('id', 125);
    $this->request->query->set('name', 'Администраторы');
    $group = $this->getMock('domain\group');
    $group->expects($this->once())
          ->method('set_name')
          ->with('Администраторы');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\group', 125)
                    ->willReturn($group);
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\update_group_name.tpl', ['group' => $group])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_group_name($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_login_1(){
    $this->request->query->set('id', 125);
    $this->request->query->set('login', 'NekrasovEV');
    $user = $this->getMock('domain\user');
    $user->expects($this->once())
         ->method('set_login')
         ->with('NekrasovEV');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn($user);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByLogin'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByLogin')
               ->willReturn(null);
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\user')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\update_fio.tpl', ['user' => $user])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_login($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_login_2(){
    $this->setExpectedException('RuntimeException');
    $this->request->query->set('id', 125);
    $this->request->query->set('login', 'NekrasovEV');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByLogin'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByLogin')
               ->willReturn('user_object');
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\user')
                    ->will($this->returnValue($repository));
    $this->controller->update_login($this->request, $this->app);
  }

  public function test_update_user_status_1(){
    $this->request->query->set('id', 125);
    $user = $this->getMock('domain\user');
    $user->expects($this->once())
         ->method('get_status')
         ->willReturn('false');
    $user->expects($this->once())
         ->method('set_status')
         ->with('true');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn($user);
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\update_fio.tpl', ['user' => $user])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_user_status($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_user_status_2(){
    $this->request->query->set('id', 125);
    $user = $this->getMock('domain\user');
    $user->expects($this->once())
         ->method('get_status')
         ->willReturn('true');
    $user->expects($this->once())
         ->method('set_status')
         ->with('false');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn($user);
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\update_fio.tpl', ['user' => $user])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_user_status($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_password_1(){
    $this->setExpectedException('RuntimeException');
    $this->request->query->set('id', 125);
    $this->request->query->set('password', 'Aa123456');
    $this->request->query->set('confirm', 'Aa1654321');
    $this->controller->update_password($this->request, $this->app);
  }

  public function test_update_password_2(){
    $this->request->query->set('id', 125);
    $this->request->query->set('password', 'Aa123456');
    $this->request->query->set('confirm', 'Aa123456');
    $user = $this->getMock('domain\user');
    $user->expects($this->once())
         ->method('set_hash')
         ->with('4b31f049d1f07d54138c2cbe964458e2');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn($user);
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['salt'] = 'salt';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('user\update_fio.tpl', ['user' => $user])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_password($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_restriction(){
    $user = $this->getMock('domain\user');
    $user->expects($this->once())
         ->method('update_restriction')
         ->with('departments', 79);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn($user);
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $response = $this->controller->update_restriction($this->app, 125, 'departments', 79);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_update_access(){
    $user = $this->getMock('domain\user');
    $user->expects($this->once())
         ->method('update_access')
         ->with('queries/general_access');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\user', 125)
                    ->willReturn($user);
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $response = $this->controller->update_access($this->app, 125, 'queries', 'general_access');
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }
}