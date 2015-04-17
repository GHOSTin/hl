<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\auth as controller;

class controller_auth_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()
                 ->getMock();
    $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
           ->disableOriginalConstructor()
           ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->app['twig'] = $twig;
    $this->app['em'] = $em;
  }

  public function test_login_1(){
    $this->request->query->set('login', 'wronglogin');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByLogin'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByLogin')
               ->with('wronglogin')
               ->willReturn(null);
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\user')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('auth/form.tpl',
                            [
                             'user' => null,
                             'error' => 'USER_NOT_EXIST',
                             'login' => 'wronglogin',
                             'password' => null
                            ])
                      ->willReturn('render_template');
    $response = $this->controller->login($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_login_3(){
    $this->request->query->set('login', 'NekrasovEV');
    $this->request->query->set('password', 'wrong_password');
    $user = $this->getMock('domain\user');
    $user->expects($this->once())
         ->method('get_hash')
         ->willReturn('wrong_hash');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByLogin'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByLogin')
               ->with('NekrasovEV')
               ->will($this->returnValue($user));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\user')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('auth/form.tpl',
                            [
                             'user' => null,
                             'error' => 'WRONG_PASSWORD',
                             'login' => 'NekrasovEV',
                             'password' => null
                            ])
                      ->willReturn('render_template');
    $this->app['salt'] = 'salt';
    $response = $this->controller->login($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_login_4(){
    $this->request->query->set('login', 'NekrasovEV');
    $this->request->query->set('password', 'password');
    $user = $this->getMock('domain\user');
    $user->expects($this->once())
         ->method('get_hash')
         ->willReturn('d514dee5e76bbb718084294c835f312c');
    $user->expects($this->once())
         ->method('get_id')
         ->willReturn(125);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByLogin'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByLogin')
               ->with('NekrasovEV')
               ->will($this->returnValue($user));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\user')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('persist')
                    ->with($this->isInstanceOf('domain\session'));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $session = $this->getMock('Symfony\Component\HttpFoundation\Session\Session');
    $session->expects($this->once())
            ->method('set')
            ->with('user', 125);
    $this->app['session'] = $session;
    $this->app['salt'] = 'salt';
    $response = $this->controller->login($this->request, $this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
    $this->assertEquals('/', $response->getTargetUrl());
  }

  public function test_login_form(){
    $this->app['user'] = null;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('auth/form.tpl',
                            [
                             'user' => null,
                             'error' => null,
                             'login' => null,
                             'password' => null
                            ])
                      ->willReturn('render_template');
    $response = $this->controller->login_form($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_logout(){
    $session = $this->getMock('Symfony\Component\HttpFoundation\Session\Session');
    $session->expects($this->once())
            ->method('invalidate');
    $this->app['session'] = $session;
    $response = $this->controller->logout($this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
    $this->assertEquals('/enter/', $response->getTargetUrl());
  }
}