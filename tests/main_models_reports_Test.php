<?php

use Silex\Application;
use main\models\reports as model;

class main_models_reports_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('reports/general_access')
               ->willReturn(true);
    $this->app = new Application();
  }

  public function test_default_page(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('report\default_page.tpl', ['user' => $this->user])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->user);
    $this->assertEquals('render_template', $model->default_page());
  }
}