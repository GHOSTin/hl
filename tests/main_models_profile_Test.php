<?php

use Silex\Application;
use main\models\profile as model;

class main_model_profile_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->app = new Application();
  }

  public function test_default_page(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('profile\default_page.tpl', ['user' => $this->user])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('render_template', $model->default_page());
  }

  public function test_get_user_info(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('profile\get_userinfo.tpl', ['user' => $this->user])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('render_template', $model->get_user_info());
  }

  public function test_update_cellphone(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('profile\get_userinfo.tpl', ['user' => $this->user])
               ->willReturn('render_template');
    $this->user->expects($this->once())
               ->method('set_cellphone')
               ->with('+79222944742');
    $this->em->expects($this->once())
             ->method('flush');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('render_template', $model->update_cellphone('+79222944742'));
  }

  public function test_update_password_1(){
    $this->setExpectedException('RuntimeException');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('render_template', $model->update_password('Aa123456', 'Aa654321', 'salt'));
  }

  public function test_update_password_2(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('profile\get_userinfo.tpl', ['user' => $this->user])
               ->willReturn('render_template');
    $this->user->expects($this->once())
               ->method('set_hash')
               ->with('4b31f049d1f07d54138c2cbe964458e2');
    $this->em->expects($this->once())
             ->method('flush');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('render_template', $model->update_password('Aa123456', 'Aa123456', 'salt'));
  }

  public function test_update_telephone(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('profile\get_userinfo.tpl', ['user' => $this->user])
               ->willReturn('render_template');
    $this->user->expects($this->once())
               ->method('set_telephone')
               ->with('647957');
    $this->em->expects($this->once())
             ->method('flush');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals('render_template', $model->update_telephone('647957'));
  }
}