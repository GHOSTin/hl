<?php

use Silex\Application;
use main\models\numbers as model;

class main_model_numbers_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->number = $this->getMock('domain\number');
    $this->app = new Application();
  }

  public function test_construct(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(false);
    new model($this->twig, $this->em, $this->user);
  }

  public function test_default_page(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('number\default_page.tpl',
                     [
                      'user' => $this->user,
                      'streets' => 'street_array'
                     ])
               ->will($this->returnValue('render_template'));
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $model = $this->getMockBuilder('main\models\numbers')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user])
                  ->setMethods(['get_streets'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_streets')
          ->willReturn('street_array');
    $this->assertEquals('render_template', $model->default_page());
  }

  public function test_get_street_content(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $this->twig->expects($this->exactly(2))
               ->method('render')
               ->withConsecutive(
                                  [
                                    'number\build_houses_titles.tpl',
                                    ['houses' => 'houses_array']
                                  ],
                                  [
                                    'number\get_street_content.tpl',
                                    ['street' => 'street_object']
                                  ]
                                )
               ->will($this->onConsecutiveCalls('workspace', 'path'));
    $street_model = $this->getMockBuilder('main\models\street')
                  ->disableOriginalConstructor()
                  ->getMock();
    $street_model->expects($this->once())
          ->method('get_street')
          ->willReturn('street_object');
    $street_model->expects($this->once())
          ->method('get_houses')
          ->willReturn('houses_array');
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals(
                        [
                          'workspace' => 'workspace',
                          'path' => 'path'
                        ],
                        $model->get_street_content($street_model)
                      );
  }

  public function test_get_house_content(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\house', 125)
             ->willReturn('house_object');
    $this->twig->expects($this->exactly(2))
               ->method('render')
               ->withConsecutive(
                                  [
                                    'number/build_house_content.tpl',
                                    ['house' => 'house_object']
                                  ],
                                  [
                                    'number/get_house_content.tpl',
                                    ['house' => 'house_object']
                                  ]
                                )
               ->will($this->onConsecutiveCalls('workspace', 'path'));
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals(
                        [
                          'workspace' => 'workspace',
                          'path' => 'path'
                        ],
                        $model->get_house_content(125)
                      );
  }

  public function test_get_number_content(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\number', 125)
             ->willReturn('number_object');
    $this->twig->expects($this->exactly(2))
               ->method('render')
               ->withConsecutive(
                                  [
                                    'number/build_number_fio.tpl',
                                    [
                                      'number' => 'number_object',
                                      'user' => $this->user
                                    ]
                                  ],
                                  [
                                    'number/get_number_content.tpl',
                                    ['number' => 'number_object']
                                  ]
                                )
               ->will($this->onConsecutiveCalls('workspace', 'path'));
    $model = new model($this->twig, $this->em, $this->user);
    $this->assertEquals(
                        [
                          'workspace' => 'workspace',
                          'path' => 'path'
                        ],
                        $model->get_number_content(125)
                      );
  }
  public function test_streets(){
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('number/build_street_titles.tpl', ['streets' => 'street_array'])
               ->will($this->returnValue('workspace'));
    $model = $this->getMockBuilder('main\models\numbers')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user])
                  ->setMethods(['get_streets'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_streets')
          ->willReturn('street_array');
    $this->assertEquals(['workspace' => 'workspace'], $model->streets());
  }
}