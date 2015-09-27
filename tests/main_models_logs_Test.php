<?php

use Silex\Application;
use main\models\logs as model;

class main_model_logs_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->filesystem = $this->getMockBuilder('League\Flysystem\Filesystem')
                             ->disableOriginalConstructor()
                             ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->app = new Application();
  }

  public function test_construct_1(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(false);
    new model($this->twig, $this->user, $this->filesystem);
  }

  public function test_construct_2(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->exactly(2))
               ->method('check_access')
               ->withConsecutive(['system/general_access'],['system/logs'])
               ->will($this->onConsecutiveCalls(true, false));
    new model($this->twig, $this->user, $this->filesystem);
  }

  public function test_client(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('logs/client.tpl',
                      [
                        'user' => $this->user,
                        'rows' => 'rows_array'
                      ])
               ->willReturn('render_template');
    $this->user->expects($this->exactly(2))
               ->method('check_access')
               ->withConsecutive(['system/general_access'],['system/logs'])
               ->will($this->onConsecutiveCalls(true, true));
    $model = $this->getMockBuilder('main\models\logs')
                  ->setConstructorArgs([$this->twig, $this->user, $this->filesystem])
                  ->setMethods(['get_rows'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_rows')
          ->with('auth_client.log')
          ->willReturn('rows_array');
    $this->assertEquals('render_template', $model->client());
  }

  public function test_default_page(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('logs/default_page.tpl', ['user' => $this->user])
               ->willReturn('render_template');
    $this->user->expects($this->exactly(2))
               ->method('check_access')
               ->withConsecutive(['system/general_access'],['system/logs'])
               ->will($this->onConsecutiveCalls(true, true));
    $model = new model($this->twig, $this->user, $this->filesystem);
    $this->assertEquals('render_template', $model->default_page());
  }

  public function test_main(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('logs/client.tpl',
                      [
                        'user' => $this->user,
                        'rows' => 'rows_array'
                      ])
               ->willReturn('render_template');
    $this->user->expects($this->exactly(2))
               ->method('check_access')
               ->withConsecutive(['system/general_access'],['system/logs'])
               ->will($this->onConsecutiveCalls(true, true));
    $model = $this->getMockBuilder('main\models\logs')
                  ->setConstructorArgs([$this->twig, $this->user, $this->filesystem])
                  ->setMethods(['get_rows'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_rows')
          ->with('auth_main.log')
          ->willReturn('rows_array');
    $this->assertEquals('render_template', $model->main());
  }
}