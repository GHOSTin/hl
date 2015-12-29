<?php namespace tests\main\models;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\api as controller;
use domain\user;
use PHPUnit_Framework_TestCase;

class api_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()->getMock();
    $this->app = new Application();
    $this->request = new Request();
    $this->controller = new controller();
  }

  public function test_get_chat_options_1(){
    $this->app['user'] = null;
    $app = $this->getMock('\Silex\Application');
    $app->expects($this->once())
        ->method('json')
        ->with([], 400)
        ->will($this->returnValue('json_response'));
    $response = $this->controller->get_chat_options($app);
    $this->assertEquals('json_response', $response);
  }

  public function test_get_chat_options_2(){
    $app = $this->getMockBuilder('\Silex\Application')
                ->setMethods(['json'])
                ->getMock();
    $user = new user();
    $user->set_id(123);
    $app['user'] = $user;
    $app['chat_host'] = 'example.com';
    $app['chat_port'] = 5000;
    $array = ['user' => 123, 'host' => $app['chat_host'],
              'port' => $app['chat_port']];
    $app->expects($this->once())
        ->method('json')
        ->with($array)
        ->will($this->returnValue('json_response'));
    $response = $this->controller->get_chat_options($app);
    $this->assertEquals('json_response', $response);
  }

  public function test_get_users(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->will($this->returnValue('user_array'));
    $this->em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                ->setMethods(['getRepository'])
               ->disableOriginalConstructor()->getMock();
    $this->em->expects($this->once())
             ->method('getRepository')
             ->will($this->returnValue($repository));
    $app = $this->getMockBuilder('\Silex\Application')
                ->setMethods(['json'])
                ->getMock();
    $app['em'] = $this->em;
    $app->expects($this->once())
        ->method('json')
        ->with('user_array')
        ->will($this->returnValue('json_response'));
    $response = $this->controller->get_users($app);
    $this->assertEquals('json_response', $response);
  }

  public function test_get_user_by_login_and_password_1(){
    $this->request->query->set('login', 'NekrasovEV');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByLogin'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByLogin')
               ->with('NekrasovEV')
               ->will($this->returnValue(null));
    $this->em->expects($this->once())
             ->method('getRepository')
             ->will($this->returnValue($repository));
    $app = $this->getMockBuilder('\Silex\Application')
                ->setMethods(['json'])
                ->getMock();
    $app['em'] = $this->em;
    $app->expects($this->once())
        ->method('json')
        ->with(null)
        ->will($this->returnValue('json_response'));
    $response = $this->controller
                     ->get_user_by_login_and_password($this->request, $app);
    $this->assertEquals('json_response', $response);
  }

  public function test_get_user_by_login_and_password_2(){
    $this->request->query->set('login', 'NekrasovEV');
    $this->request->query->set('password', 'password');
    $user = new user();
    $user->set_hash(user::generate_hash('password', 'salt'));
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByLogin'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByLogin')
               ->with('NekrasovEV')
               ->will($this->returnValue($user));
    $this->em->expects($this->once())
             ->method('getRepository')
             ->will($this->returnValue($repository));
    $app = $this->getMockBuilder('\Silex\Application')
                ->setMethods(['json'])
                ->getMock();
    $app['salt'] = 'salt';
    $app['em'] = $this->em;
    $app->expects($this->once())
        ->method('json')
        ->with($user)
        ->will($this->returnValue('json_response'));
    $response = $this->controller
                     ->get_user_by_login_and_password($this->request, $app);
    $this->assertEquals('json_response', $response);
  }
  public function test_get_user_by_login_and_password_3(){
    $this->request->query->set('login', 'NekrasovEV');
    $this->request->query->set('password', 'password');
    $user = new user();
    $user->set_hash('wrong_hash');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByLogin'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByLogin')
               ->with('NekrasovEV')
               ->will($this->returnValue($user));
    $this->em->expects($this->once())
             ->method('getRepository')
             ->will($this->returnValue($repository));
    $app = $this->getMockBuilder('\Silex\Application')
                ->setMethods(['json'])
                ->getMock();
    $app['salt'] = 'salt';
    $app['em'] = $this->em;
    $app->expects($this->once())
        ->method('json')
        ->with(null)
        ->will($this->returnValue('json_response'));
    $response = $this->controller
                     ->get_user_by_login_and_password($this->request, $app);
    $this->assertEquals('json_response', $response);
  }
}