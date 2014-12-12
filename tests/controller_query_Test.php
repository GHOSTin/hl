<?php

use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \main\controllers\queries as controller;
use \domain\user;

class controller_query_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()->getMock();
    $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()->getMock();
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
                      ->with('query\add_comment.tpl',
                             ['query' => $query])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->add_comment($this->request, $this->app);
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
                      ->with('query\get_dialog_add_comment.tpl',
                             ['query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_add_comment($this->request,
                                                          $this->app);
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
                      ->with('query\get_dialog_add_work.tpl',
                             ['query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_add_work($this->request,
                                                       $this->app);
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
                      ->with('query\get_query_comments.tpl',
                             ['query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_query_comments($this->request,
                                                     $this->app);
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
                      ->with('query\get_query_content.tpl',
                             ['query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_query_content($this->request,
                                                     $this->app);
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
                      ->with('query\get_documents.tpl',
                             ['query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_documents($this->request,
                                                     $this->app);
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
    $response = $this->controller->get_dialog_initiator($this->request,
                                                        $this->app);
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
    $response = $this->controller->get_dialog_change_initiator($this->request,
                                                               $this->app);
    $this->assertEquals('render_template', $response);
  }
}