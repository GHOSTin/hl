<?php namespace tests\client\models;

use Silex\Application;
use client\models\arrears as model;
use PHPUnit_Framework_TestCase;

class arrears_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->app = new Application();
  }

  public function test_default_page(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with([], ['name' => 'ASC'])
               ->willReturn('streets_array');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\street')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('arrears/default_page.tpl', ['streets' => 'streets_array'])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em);
    $this->assertEquals('render_template', $model->default_page());
  }

  public function test_flat(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('find')
               ->with(125)
               ->willReturn('flat_object');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\flat')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('arrears/flat.tpl', ['flat' => 'flat_object'])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em);
    $this->assertEquals('render_template', $model->flat(125));
  }

  public function test_flats(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('find')
               ->with(125)
               ->willReturn('house_object');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\house')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('arrears/flats.tpl', ['house' => 'house_object'])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em);
    $this->assertEquals('render_template', $model->flats(125));
  }

  public function test_houses(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('find')
               ->with(125)
               ->willReturn('street_object');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\street')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('arrears/houses.tpl', ['street' => 'street_object'])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em);
    $this->assertEquals('render_template', $model->houses(125));
  }

  public function test_top(){
    $house = $this->getMockBuilder('domain\house')
                  ->disableOriginalConstructor()
                  ->getMock();
    $house->expects($this->once())
          ->method('get_debtors')
          ->with(50000)
          ->willReturn([100 => 3, 50 => 1 , 400 => 5]);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('find')
               ->with(125)
               ->willReturn($house);
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\house')
               ->willReturn($repository);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('arrears/top.tpl', ['debtors' => [100 => 3, 50 => 1 , 400 => 5]])
               ->willReturn('render_template');
    $model = new model($this->twig, $this->em);
    $this->assertEquals('render_template', $model->top(125, 50000));
  }
}