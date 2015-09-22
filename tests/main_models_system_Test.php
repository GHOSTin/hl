<?php

use Silex\Application;
use main\models\system as model;

class main_model_system_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->app = new Application();
  }

  public function test_construct(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(false);
    new model($this->twig, $this->user);
  }

  public function test_config_1(){
    $this->setExpectedException('RuntimeException');
    $reflection = $this->getMockBuilder('ReflectionClass')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user->expects($this->exactly(2))
               ->method('check_access')
               ->withConsecutive(['system/general_access'],['system/config'])
               ->will($this->onConsecutiveCalls(true, false));
    $model = new model($this->twig, $this->user);
    $this->assertEquals('render_template', $model->config($reflection));
  }

  public function test_config_2(){
    $reflection = $this->getMockBuilder('ReflectionClass')
                       ->disableOriginalConstructor()
                       ->getMock();
    $reflection->expects($this->once())
               ->method('getConstants')
               ->willReturn('constants');
    $this->user->expects($this->exactly(2))
               ->method('check_access')
               ->withConsecutive(['system/general_access'],['system/config'])
               ->will($this->onConsecutiveCalls(true, true));
    $this->twig->expects($this->once())
               ->method('render')
               ->with('system/config.tpl', [
                                            'user' => $this->user,
                                            'conf' => 'constants'
                                           ])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->user);
    $this->assertEquals('render_template', $model->config($reflection));
  }

  public function test_default_page(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('system/default_page.tpl', ['user' => $this->user])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->user);
    $this->assertEquals('render_template', $model->default_page());
  }
}