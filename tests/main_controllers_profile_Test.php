<?php

use Silex\Application;
use main\controllers\profile as controller;
use Symfony\Component\HttpFoundation\Request;

class main_conrollers_profile_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\profile')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->app['main\models\profile'] = $this->model;
  }

  public function test_default_page(){
    $this->model->expects($this->once())
                ->method('default_page');
    $this->controller->default_page($this->app);
  }

  public function test_get_user_info(){
    $this->model->expects($this->once())
                ->method('get_user_info');
    $this->controller->get_user_info($this->app);
  }

  public function test_update_cellphone(){
    $this->request->query->set('cellphone', '+79222944742');
    $this->model->expects($this->once())
                ->method('update_cellphone')
                ->with('+79222944742');
    $this->controller->update_cellphone($this->request, $this->app);
  }

  public function test_update_password(){
    $this->app['salt'] = 'salt';
    $this->request->query->set('new_password', 'Aa123456');
    $this->request->query->set('confirm_password', 'Aa123456');
    $this->model->expects($this->once())
                ->method('update_password')
                ->with('Aa123456', 'Aa123456', 'salt');
    $this->controller->update_password($this->request, $this->app);
  }

  public function test_update_telephone(){
    $this->request->query->set('telephone', '647957');
    $this->model->expects($this->once())
                ->method('update_telephone')
                ->with('647957');
    $this->controller->update_telephone($this->request, $this->app);
  }
}