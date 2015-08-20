<?php

use Silex\Application;
use main\models\api_keys as model;

class main_model_api_keys_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->number = $this->getMock('domain\number');
    $this->api_key = $this->getMock('domain\api_key');
    $this->app = new Application();
  }

  public function test_construct(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/api_key')
               ->willReturn(false);
    new model($this->twig, $this->em, $this->user);
  }

  public function test_create_1(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/api_key')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByName'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByName')
               ->with('Даниловское')
               ->willReturn($this->api_key);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\api_key')
             ->will($this->returnValue($repository));
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('render_template', $model->create(' Даниловское '));
  }

  public function test_create_2(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/api_key')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByName'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByName')
               ->with('Даниловское')
               ->willReturn(null);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\api_key')
             ->will($this->returnValue($repository));
    $this->em->expects($this->once())
             ->method('persist')
             ->with($this->isInstanceOf('domain\api_key'));
    $this->em->expects($this->once())
             ->method('flush');
    $this->twig->expects($this->once())
               ->method('render')
               ->with('api_keys/keys.tpl', ['keys' => 'keys_array'])
               ->willReturn('render_template');
    $model = $this->getMockBuilder('main\models\api_keys')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user])
                  ->setMethods(['get_keys'])
                  ->getMock();
    $model->expects($this->once())
         ->method('get_keys')
         ->willReturn('keys_array');
    $this->assertEquals('render_template', $model->create(' Даниловское '));
  }

  public function test_create_dialog(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/api_key')
               ->willReturn(true);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('api_keys/create_dialog.tpl')
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('render_template', $model->create_dialog());
  }

  public function test_default_page(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/api_key')
               ->willReturn(true);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('api_keys/default_page.tpl',
                      [
                        'keys' => 'keys_array',
                        'user' => $this->user
                      ])
               ->willReturn('render_template');
    $model = $this->getMockBuilder('main\models\api_keys')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user])
                  ->setMethods(['get_keys'])
                  ->getMock();
    $model->expects($this->once())
         ->method('get_keys')
         ->willReturn('keys_array');
    $this->assertEquals('render_template', $model->default_page());
  }

  public function test_get_keys(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/api_key')
               ->willReturn(true);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findBy'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with([], ['name' => 'ASC'])
               ->willReturn('keys_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\api_key')
             ->will($this->returnValue($repository));
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('keys_array', $model->get_keys());
  }
}