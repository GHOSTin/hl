<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\queries as controller;
use domain\user;

class controller_query_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()
                 ->getMock();
    $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()
               ->getMock();
    $this->request = new Request();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
    $this->app['em'] = $em;
  }

  public function test_add_comment(){
    $this->request->query->set('query_id', 125);
    $this->request->query->set('message', 'Привет');
    $comment = $this->getMock('\domain\query2comment');
    $comment->expects($this->once())
            ->method('set_user')
            ->with($this->isInstanceOf('\domain\user'));
    $comment->expects($this->once())
            ->method('set_query')
            ->with($this->isInstanceOf('\domain\query'));
    $comment->expects($this->once())
            ->method('set_time');
    $comment->expects($this->once())
            ->method('set_message')
            ->with('Привет');
    $this->app['\domain\query2comment'] = $comment;
    $query = $this->getMock('\domain\query');
    $query->expects($this->once())
          ->method('add_comment')
          ->with($this->isInstanceOf('\domain\query2comment'));
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\query', 125)
                    ->will($this->returnValue($query));
    $this->app['em']->expects($this->once())
                    ->method('persist')
                    ->with($this->isInstanceOf('\domain\query2comment'));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['user'] = new user();
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\add_comment.tpl', ['query' => $query])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->add_comment($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_clear_filters(){
    $model = $this->getMockBuilder('main\models\query')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('init_default_params');
    $model->expects($this->once())
          ->method('get_queries');
    $model->expects($this->once())
          ->method('get_timeline');
    $this->app['\main\models\query'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\clear_filters.tpl', $this->anything())
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->clear_filters($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_close_query_1(){
    $this->setExpectedException('RuntimeException');
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->will($this->returnValue(null));
    $this->controller->close_query($this->request, $this->app);
  }

  public function test_close_query_2(){
    $this->request->query->set('id', 125);
    $this->request->query->set('reason', 'Привет');
    $query = $this->getMock('\domain\query');
    $query->expects($this->once())
          ->method('close')
          ->with($this->anything(), 'Привет');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->will($this->returnValue($query));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_query_content.tpl', ['query' => $query])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->close_query($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_add_comment(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\query', 125)
                    ->will($this->returnValue('query_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_dialog_add_comment.tpl', ['query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_add_comment($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_add_work(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\query', 125)
                    ->will($this->returnValue('query_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_dialog_add_work.tpl', ['query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_add_work($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_query_comments(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\query', 125)
                    ->will($this->returnValue('query_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_query_comments.tpl', ['query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_query_comments($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_query_content(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\query', 125)
                    ->will($this->returnValue('query_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_query_content.tpl', ['query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_query_content($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_documents(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\query', 125)
                    ->will($this->returnValue('query_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_documents.tpl', ['query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_documents($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_create_query(){
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_dialog_create_query.tpl')
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_create_query($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_initiator(){
    $this->request->query->set('value', 125);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with([], ['name' => 'ASC'])
               ->will($this->returnValue('street_array'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\street')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_dialog_initiator.tpl',
                             ['streets' => 'street_array',
                              'value' => 125])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_initiator($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_change_initiator(){
    $this->request->query->set('id', 125);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findBy')
               ->with([], ['name' => 'ASC'])
               ->will($this->returnValue('street_array'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('\domain\street')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\query', 125)
                    ->will($this->returnValue('query_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_dialog_change_initiator.tpl',
                             ['streets' => 'street_array',
                              'query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_change_initiator($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_houses(){
    $this->request->query->set('id', 125);
    $model = $this->getMockBuilder('main\models\query')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_houses_by_street')
          ->with(125)
          ->will($this->returnValue('house_array'));
    $this->app['\main\models\query'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_houses.tpl', ['houses' => 'house_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_houses($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_numbers(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\house', 125)
                    ->will($this->returnValue('house_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_numbers.tpl', ['house' => 'house_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_numbers($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_timeline(){
    $this->request->query->set('time', 1397562800);
    $this->request->query->set('act', 'next');
    $model = $this->getMockBuilder('main\models\query')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('set_time');
    $model->expects($this->once())
          ->method('get_queries');
    $model->expects($this->once())
          ->method('get_timeline');
    $this->app['\main\models\query'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_timeline.tpl', $this->anything())
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_timeline($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_reclose_query_1(){
    $this->setExpectedException('RuntimeException');
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->will($this->returnValue(null));
    $this->controller->reclose_query($this->request, $this->app);
  }

  public function test_reclose_query_2(){
    $this->request->query->set('id', 125);
    $query = $this->getMock('\domain\query');
    $query->expects($this->once())
          ->method('reclose');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->will($this->returnValue($query));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_query_content.tpl', ['query' => $query])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->reclose_query($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_reopen_query_1(){
    $this->setExpectedException('RuntimeException');
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->will($this->returnValue(null));
    $this->controller->reopen_query($this->request, $this->app);
  }

  public function test_reopen_query_2(){
    $this->request->query->set('id', 125);
    $query = $this->getMock('\domain\query');
    $query->expects($this->once())
          ->method('reopen');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->will($this->returnValue($query));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_query_content.tpl', ['query' => $query])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->reopen_query($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_to_working_query_1(){
    $this->setExpectedException('RuntimeException');
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->will($this->returnValue(null));
    $this->controller->to_working_query($this->request, $this->app);
  }

  public function test_to_working_query_2(){
    $this->request->query->set('id', 125);
    $query = $this->getMock('\domain\query');
    $query->expects($this->once())
          ->method('to_work');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->will($this->returnValue($query));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_query_content.tpl', ['query' => $query])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->to_working_query($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }
}