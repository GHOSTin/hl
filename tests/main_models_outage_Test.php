<?php

use Silex\Application;
use main\models\outage as model;

class main_model_outage_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->outage = $this->getMock('domain\outage');
    $this->app = new Application();
  }

  public function test_construct_1(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(false);
    new model($this->twig, $this->em, $this->user, 125);
  }

  public function test_construct_2(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\outage', 125)
             ->willReturn(null);
    new model($this->twig, $this->em, $this->user, 125);
  }

  public function test_get_edit_dialog(){
    $this->user->expects($this->exactly(2))
               ->method('check_access')
               ->withConsecutive(
                                  ['numbers/general_access'],
                                  ['numbers/create_outage']
                                )
               ->will($this->onConsecutiveCalls(true, true));
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\outage', 125)
             ->willReturn($this->outage);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->exactly(3))
               ->method('findBy')
               ->with([], ['name' => 'ASC'])
               ->will($this->onConsecutiveCalls('workgroups_array', 'streets_array', 'groups_array'));
    $this->em->expects($this->exactly(3))
             ->method('getRepository')
             ->withConsecutive(['domain\workgroup'], ['domain\street'], ['domain\group'])
             ->will($this->returnValue($repository));
    $this->twig->expects($this->once())
               ->method('render')
               ->with('outage\edit_dialog.tpl', [
                                                  'workgroups' => 'workgroups_array',
                                                  'streets' => 'streets_array',
                                                  'groups' => 'groups_array',
                                                  'outage' => $this->outage
                                                ]);
    $model = new model($this->twig, $this->em, $this->user, 125);
    $model->get_edit_dialog();
  }

  public function test_get_edit_dialog_2(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->exactly(2))
               ->method('check_access')
               ->withConsecutive(
                                  ['numbers/general_access'],
                                  ['numbers/create_outage']
                                )
               ->will($this->onConsecutiveCalls(true, false));
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\outage', 125)
             ->willReturn($this->outage);
    $model = new model($this->twig, $this->em, $this->user, 125);
    $model->get_edit_dialog();
  }
}