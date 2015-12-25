<?php namespace tests\main\models;

use main\models\report_event as model;
use PHPUnit_Framework_TestCase;

class event_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->session = $this->getMock('Symfony\Component\HttpFoundation\Session\Session');
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->default_params = [
                             'time_begin' => 100,
                             'time_end' => 200
                            ];
  }

  public function test_constructor(){
    $this->session->expects($this->once())
                  ->method('get')
                  ->with('report_event')
                  ->willReturn([]);
    $model = $this->getMockBuilder('main\models\report_event')
                  ->setConstructorArgs([$this->em, $this->session])
                  ->getMock();
  }

  public function test_init_default_params(){
    $params['time_begin'] = strtotime('midnight');
    $params['time_end'] = strtotime('tomorrow');
    $this->session->expects($this->once())
                  ->method('get')
                  ->with('report_event')
                  ->willReturn([]);
    $model = $this->getMockBuilder('main\models\report_event')
                  ->setConstructorArgs([$this->em, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with($params);
    $model->init_default_params();
  }

  public function test_set_time_begin(){
    $time = time();
    $params['time_begin'] = $time;
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_event')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_event')
                  ->setConstructorArgs([$this->em, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['time_begin' => $time]);
    $model->set_time_begin($time);
  }

  public function test_set_time_end(){
    $time = time();
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_event')
                  ->willReturn($this->default_params);
    $model = $this->getMockBuilder('main\models\report_event')
                  ->setConstructorArgs([$this->em, $this->session])
                  ->setMethods(['save_params'])
                  ->getMock();
    $model->expects($this->once())
          ->method('save_params')
          ->with(['time_end' => $time]);
    $model->set_time_end($time);
  }

  public function test_get_events(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_event')
                  ->willReturn($this->default_params);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findByParams'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findByParams')
               ->with($this->default_params)
               ->willReturn('events_array');
    $this->em->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\number2event')
                    ->will($this->returnValue($repository));
    $model = new model($this->em, $this->session);
    $this->assertEquals('events_array', $model->get_events());
  }

  public function test_get_filters(){
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_event')
                  ->willReturn($this->default_params);
    $model = new model($this->em, $this->session);
    $this->assertEquals($this->default_params, $model->get_filters());
  }

  public function test_save_params(){
    $params = [
               'time_begin' => 12,
               'time_end' => 24
              ];
    $this->session->expects($this->exactly(2))
                  ->method('get')
                  ->with('report_event')
                  ->willReturn($this->default_params);
    $this->session->expects($this->once())
                  ->method('set')
                  ->with('report_event', $params);
    $model = new model($this->em, $this->session);
    $model->save_params($params);
  }
}