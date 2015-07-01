<?php

use client\models\queries as model;
use Doctrine\Common\Collections\ArrayCollection;
use domain\query;

class client_models_queries_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->number = $this->getMock('domain\number');
  }

  public function test_default_page(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('queries/default_page.tpl',
                      [
                        'number' => $this->number,
                        'queries' => 'array_queries',
                        'requests' => 'array_number_request',
                        'count' => 5
                      ])
               ->will($this->returnValue('render_template'));
    $model = $this->getMockBuilder('client\models\queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->number])
                  ->setMethods(['get_queries', 'get_number_request', 'count_24_hours_number_request'])
                  ->getMock();
    $model->expects($this->once())
         ->method('get_queries')
         ->willReturn('array_queries');
    $model->expects($this->once())
          ->method('get_number_request')
          ->willReturn('array_number_request');
    $model->expects($this->once())
          ->method('count_24_hours_number_request')
          ->willReturn(5);
    $this->assertEquals('render_template', $model->default_page());
  }

  public function test_get_number_request(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with([
                        'query' => null,
                        'number' => $this->number
                      ],
                      ['time' => 'DESC'])
               ->willReturn('array_number_request');
    $this->em->expects($this->once())
               ->method('getRepository')
               ->with('domain\number_request')
               ->willReturn($repository);
    $model = new model($this->twig, $this->em, $this->number);
    $this->assertEquals('array_number_request', $model->get_number_request());
  }

  public function test_get_queries(){
    $query = new query();
    $query->set_initiator('number');
    $collection = new ArrayCollection();
    $collection->add($query);
    $this->number->expects($this->once())
                 ->method('get_queries')
                 ->willReturn($collection);
    $model = new model($this->twig, $this->em, $this->number);
    $this->assertEquals($collection, $model->get_queries());
  }

  public function test_request_1(){
    $model = $this->getMockBuilder('client\models\queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->number])
                  ->setMethods(['count_24_hours_number_request'])
                  ->getMock();
    $model->expects($this->once())
          ->method('count_24_hours_number_request')
          ->willReturn(1);
    $response = $model->request();
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
    $this->assertEquals('/queries/', $response->getTargetUrl());
  }

  public function test_request_2(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('queries/request.tpl', ['number' => $this->number])
               ->will($this->returnValue('render_template'));
    $model = $this->getMockBuilder('client\models\queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->number])
                  ->setMethods(['count_24_hours_number_request'])
                  ->getMock();
    $model->expects($this->once())
          ->method('count_24_hours_number_request')
          ->willReturn(0);
    $this->assertEquals('render_template', $model->request());
  }

  public function test_send_request_1(){
    $model = $this->getMockBuilder('client\models\queries')
                  ->disableOriginalConstructor()
                  ->setMethods(['count_24_hours_number_request'])
                  ->getMock();
    $model->expects($this->once())
          ->method('count_24_hours_number_request')
          ->willReturn(1);
    $response = $model->send_request('Описание заявки');
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
    $this->assertEquals('/queries/', $response->getTargetUrl());
  }

  public function test_send_request_2(){
    $this->em->expects($this->once())
             ->method('persist')
             ->with($this->isInstanceOf('domain\number_request'));
    $this->em->expects($this->once())
             ->method('flush');
    $model = $this->getMockBuilder('client\models\queries')
                  ->setConstructorArgs([$this->twig, $this->em, $this->number])
                  ->setMethods(['count_24_hours_number_request'])
                  ->getMock();
    $model->expects($this->once())
          ->method('count_24_hours_number_request')
          ->willReturn(0);
    $response = $model->send_request('Описание заявки');
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
    $this->assertEquals('/queries/', $response->getTargetUrl());
  }
}