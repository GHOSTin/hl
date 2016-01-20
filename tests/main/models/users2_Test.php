<?php namespace tests\main\models;

use Silex\Application;
use main\models\users as model;
use PHPUnit_Framework_TestCase;

class users2_Test extends PHPUnit_Framework_TestCase{

  const user_login = 'NekrasovEV';

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(true);
    $this->app = new Application();
    $this->model = new model($this->twig, $this->em, $this->user);
  }


  public function test_create_user_1(){
    $this->setExpectedException('RuntimeException');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByLogin'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByLogin')
               ->with(self::user_login)
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\user')
             ->will($this->returnValue($repository));
    $this->model->create_user('Некрасов', 'Евгений', 'Валерьвич', self::user_login, 'hash');
  }

  public function test_create_user_2(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByLogin'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByLogin')
               ->with(self::user_login)
               ->willReturn(null);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\user')
             ->will($this->returnValue($repository));
    $this->em->expects($this->once())
             ->method('persist')
             ->with($this->isInstanceOf('domain\user'));
    $this->em->expects($this->once())
             ->method('flush');
    $user = $this->model->create_user('Некрасов', 'Евгений', 'Валерьвич', self::user_login, 'hash');
    $this->assertInstanceOf('domain\user', $user);
  }

  public function test_get_dialog_create_user(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('user\get_dialog_create_user.tpl')
               ->willReturn('render_template');
    $this->assertEquals('render_template', $this->model->get_dialog_create_user());
  }

  public function test_default_page(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('user\default_page.tpl', ['user' => $this->user])
               ->willReturn('render_template');
    $this->assertEquals('render_template', $this->model->default_page());
  }

  public function test_get_users(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with([], ['lastname' => 'ASC'])
               ->willReturn('users_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\user')
             ->will($this->returnValue($repository));
    $this->twig->expects($this->once())
               ->method('render')
               ->with('user\get_users.tpl', ['users' => 'users_array'])
               ->willReturn('render_template');
    $this->assertEquals('render_template', $this->model->get_users());
  }
}