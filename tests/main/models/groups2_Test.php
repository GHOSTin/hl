<?php namespace tests\main\models;

use Silex\Application;
use main\models\groups as model;
use PHPUnit_Framework_TestCase;

class groups2_Test extends PHPUnit_Framework_TestCase{

  const group_name = 'Сантехники';

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

  public function test_create_group_1(){
    $this->setExpectedException('RuntimeException');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByName'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByName')
               ->with(self::group_name)
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\group')
             ->will($this->returnValue($repository));
    $this->model->create_group(self::group_name);
  }

  public function test_create_group_2(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByName'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByName')
               ->with(self::group_name)
               ->willReturn(null);
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\group')
             ->will($this->returnValue($repository));
    $this->em->expects($this->once())
             ->method('persist')
             ->with($this->isInstanceOf('domain\group'));
    $this->em->expects($this->once())
             ->method('flush');
    $group = $this->model->create_group(self::group_name);
    $this->assertInstanceOf('domain\group', $group);
  }

  public function test_get_dialog_create_group(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('user\get_dialog_create_group.tpl')
               ->willReturn('render_template');
    $this->assertEquals('render_template', $this->model->get_dialog_create_group());
  }

  public function test_get_groups(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with([], ['name' => 'ASC'])
               ->willReturn('groups_array');
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\group')
             ->will($this->returnValue($repository));
    $this->twig->expects($this->once())
               ->method('render')
               ->with('user\get_groups.tpl', ['groups' => 'groups_array'])
               ->willReturn('render_template');
    $this->assertEquals('render_template', $this->model->get_groups());
  }
}