<?php namespace tests\main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\queries as controller;
use domain\user;
use domain\query_type;
use domain\workgroup;
use domain\query;
use domain\number;
use domain\number_request;
use PHPUnit_Framework_TestCase;

class queries_Test extends PHPUnit_Framework_TestCase{

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

  public function test_outages(){
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('outages')
          ->willReturn(['render_template']);
    $this->app['main\models\queries'] = $model;
    $response = $this->controller->outages($this->app);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }

  public function test_abort_query_from_request(){
    $this->request->query->set('description', 'Описание');
    $this->request->query->set('time', '1397562800');
    $this->request->query->set('number', '125');
    $this->request->query->set('work_type', '127');
    $this->request->query->set('query_type', '253');
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('abort_query_from_request')
          ->with('Описание', '1397562800', '127', '253', '125')
          ->willReturn('render_template');
    $this->app['main\models\queries'] = $model;
    $response = $this->controller->abort_query_from_request($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_abort_query_from_request_dialog(){
    $this->request->query->set('time', '1397562800');
    $this->request->query->set('number', '125');
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('abort_query_from_request_dialog')
          ->with('125', '1397562800')
          ->willReturn('render_template');
    $this->app['main\models\queries'] = $model;
    $response = $this->controller->abort_query_from_request_dialog($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_add_file(){
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->willReturn('query_object');
    $file = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
                 ->disableOriginalConstructor()
                 ->getMock();
    $file->expects($this->once())
         ->method('isValid')
         ->willReturn(false);
    $this->request->files->set('file', $file);
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\query_files.tpl', ['query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->add_file($this->request, $this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_count(){
    $model = $this->getMockBuilder('main\models\number_request')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('count')
          ->willReturn('render_template');
    $this->app['main\models\number_request'] = $model;
    $response = $this->controller->count($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_create_query(){
    $this->request->query->set('description', 'Описание');
    $this->request->query->set('initiator', 'number');
    $this->request->query->set('fio', 'ФИО');
    $this->request->query->set('telephone', '647957');
    $this->request->query->set('cellphone', '+79222944742');
    $this->request->query->set('id', '125');
    $this->request->query->set('work_type', '127');
    $this->request->query->set('query_type', '253');
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('create_query')
          ->with('Описание', 'number', '127', '253', 'ФИО', '647957', '+79222944742', '125')
          ->willReturn('render_template');
    $this->app['main\models\queries'] = $model;
    $response = $this->controller->create_query($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_create_query_from_request(){
    $this->request->query->set('description', 'Описание');
    $this->request->query->set('time', '1397562800');
    $this->request->query->set('number', '125');
    $this->request->query->set('work_type', '127');
    $this->request->query->set('query_type', '253');
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('create_query_from_request')
          ->with('Описание', '1397562800', '127', '253', '125')
          ->willReturn('render_template');
    $this->app['main\models\queries'] = $model;
    $response = $this->controller->create_query_from_request($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_create_query_from_request_dialog(){
    $this->request->query->set('time', '1397562800');
    $this->request->query->set('number', '125');
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('create_query_from_request_dialog')
          ->with('125', '1397562800')
          ->willReturn('render_template');
    $this->app['main\models\queries'] = $model;
    $response = $this->controller->create_query_from_request_dialog($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }


  public function test_delete_file(){
    $q2f = $this->getMockBuilder('domain\file')
                ->disableOriginalConstructor()
                ->getMock();
    $q2f->expects($this->once())
        ->method('get_path')
        ->willReturn('20150410/249d2c7dbd66604b90938aa1d7093f711ec4b77e.jpg');
    $query = $this->getMock('domain\query');
    $query->expects($this->once())
          ->method('delete_file')
          ->with($q2f);
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('find')
               ->with('20150410/249d2c7dbd66604b90938aa1d7093f711ec4b77e.jpg')
               ->willReturn($q2f);
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\file')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->will($this->returnValue($query));
    $filesystem = $this->getMockBuilder('League\Flysystem\Filesystem')
                       ->disableOriginalConstructor()
                       ->getMock();
    $filesystem->expects($this->once())
               ->method('delete')
               ->with('20150410/249d2c7dbd66604b90938aa1d7093f711ec4b77e.jpg');
    $this->app['filesystem'] = $filesystem;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\query_files.tpl', ['query' => $query])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->delete_file($this->app, 125, 20150410, '249d2c7dbd66604b90938aa1d7093f711ec4b77e.jpg');
    $this->assertEquals('render_template', $response);
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
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('init_default_params');
    $model->expects($this->once())
          ->method('get_queries');
    $model->expects($this->once())
          ->method('get_timeline');
    $this->app['main\models\queries'] = $model;
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
    $this->app['user'] = new user();
    $query->expects($this->once())
          ->method('close')
          ->with($this->app['user'], $this->anything(), 'Привет');
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

  public function test_get_dialog_change_query_type(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->will($this->returnValue('query_object'));
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->with(['name' => 'ASC'])
               ->will($this->returnValue('query_types_array'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\query_type')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_dialog_change_query_type.tpl',
                             [
                              'query' => 'query_object',
                              'query_types' => 'query_types_array'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_change_query_type($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_delete_file(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('find')
               ->with('20150410/249d2c7dbd66604b90938aa1d7093f711ec4b77e.jpg')
               ->willReturn('query2file_object');
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\file')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_dialog_delete_file.tpl', ['file' => 'query2file_object', 'query_id' => 125])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_delete_file($this->app, 125, 20150410, '249d2c7dbd66604b90938aa1d7093f711ec4b77e.jpg');
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_edit_work_type(){
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->will($this->returnValue('query_object'));
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->with(['name' => 'ASC'])
               ->will($this->returnValue('workgroups'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\workgroup')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_dialog_edit_work_type.tpl',
                             [
                              'query' => 'query_object',
                              'work_types' => 'workgroups'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_edit_work_type($this->request, $this->app);
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

  public function test_get_query_files(){
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('domain\query', 125)
                    ->will($this->returnValue('query_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_query_files.tpl', ['query' => 'query_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_query_files($this->app, 125);
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
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_streets')
          ->willReturn('street_array');
    $this->app['main\models\queries'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_dialog_initiator.tpl',
                             [
                              'streets' => 'street_array',
                              'value' => 125
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_initiator($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_change_initiator_1(){
    $query = new query();
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
                    ->will($this->returnValue($query));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\get_dialog_change_initiator.tpl',
                             ['streets' => 'street_array',
                              'query' => $query])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_change_initiator($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_change_initiator_2(){
    $this->setExpectedException('RuntimeException');
    $query = new query();
    $number = new number();
    $request = new number_request($number, 'Описание');
    $query->set_request($request);
    $this->request->query->set('id', 125);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\query', 125)
                    ->will($this->returnValue($query));
    $this->controller->get_dialog_change_initiator($this->request, $this->app);
  }

  public function test_get_houses(){
    $this->request->query->set('id', 125);
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_houses_by_street')
          ->with(125)
          ->will($this->returnValue('house_array'));
    $this->app['main\models\queries'] = $model;
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
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('set_time');
    $model->expects($this->once())
          ->method('get_queries');
    $model->expects($this->once())
          ->method('get_timeline');
    $this->app['main\models\queries'] = $model;
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
    $this->app['user'] = new user();
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
    $this->app['user'] = new user();
    $response = $this->controller->reopen_query($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_requests(){
    $model = $this->getMockBuilder('main\models\number_request')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('requests')
          ->willReturn('render_template');
    $this->app['main\models\number_request'] = $model;
    $response = $this->controller->requests($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_noclose(){
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->setMethods(['noclose'])
                  ->getMock();
    $this->request->query->set('time', 1122);
    $model->expects($this->once())
          ->method('noclose')
          ->with(1122)
          ->willReturn('render_template');
    $this->app['main\models\queries'] = $model;
    $this->assertEquals('render_template', $this->controller->noclose($this->request, $this->app));
  }

  public function test_all_noclose(){
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->setMethods(['all_noclose'])
                  ->getMock();
    $model->expects($this->once())
          ->method('all_noclose')
          ->willReturn('render_template');
    $this->app['main\models\queries'] = $model;
    $this->assertEquals('render_template', $this->controller->all_noclose($this->app));
  }

  public function test_selections(){
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->setMethods(['selections'])
                  ->getMock();
    $model->expects($this->once())
          ->method('selections')
          ->willReturn('render_template');
    $this->app['main\models\queries'] = $model;
    $this->assertEquals('render_template', $this->controller->selections($this->app));
  }

  public function test_set_department(){
    $this->request->query->set('value', 125);
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->setMethods(['set_department', 'get_queries'])
                  ->getMock();
    $model->expects($this->once())
          ->method('set_department')
          ->with(125);
    $model->expects($this->once())
          ->method('get_queries')
          ->willReturn('queries_array');
    $this->app['main\models\queries'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\query_titles.tpl', ['queries' => 'queries_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->set_department($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_house(){
    $this->request->query->set('value', 125);
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->setMethods(['set_house', 'get_queries'])
                  ->getMock();
    $model->expects($this->once())
          ->method('set_house')
          ->with(125);
    $model->expects($this->once())
          ->method('get_queries')
          ->willReturn('queries_array');
    $this->app['main\models\queries'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\query_titles.tpl', ['queries' => 'queries_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->set_house($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_query_type(){
    $this->request->query->set('value', 125);
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->setMethods(['set_query_type', 'get_queries'])
                  ->getMock();
    $model->expects($this->once())
          ->method('set_query_type')
          ->with(125);
    $model->expects($this->once())
          ->method('get_queries')
          ->willReturn('queries_array');
    $this->app['main\models\queries'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\query_titles.tpl', ['queries' => 'queries_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->set_query_type($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_status(){
    $this->request->query->set('value', 125);
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->setMethods(['set_status', 'get_queries'])
                  ->getMock();
    $model->expects($this->once())
          ->method('set_status')
          ->with(125);
    $model->expects($this->once())
          ->method('get_queries')
          ->willReturn('queries_array');
    $this->app['main\models\queries'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\query_titles.tpl', ['queries' => 'queries_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->set_status($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_street(){
    $this->request->query->set('value', 125);
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->setMethods(['set_street', 'get_queries', 'get_houses_by_street'])
                  ->getMock();
    $model->expects($this->once())
          ->method('set_street')
          ->with(125);
    $model->expects($this->once())
          ->method('get_houses_by_street')
          ->with(125)
          ->willReturn('houses_array');
    $model->expects($this->once())
          ->method('get_queries')
          ->willReturn('queries_array');
    $this->app['main\models\queries'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\set_street.tpl',
                             [
                              'queries' => 'queries_array',
                              'houses' => 'houses_array'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->set_street($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_work_type(){
    $this->request->query->set('value', 125);
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->setMethods(['set_work_type', 'get_queries'])
                  ->getMock();
    $model->expects($this->once())
          ->method('set_work_type')
          ->with(125);
    $model->expects($this->once())
          ->method('get_queries')
          ->willReturn('queries_array');
    $this->app['main\models\queries'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\query_titles.tpl', ['queries' => 'queries_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->set_work_type($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_stats(){
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_day_stats')
          ->willReturn([]);
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\stats.tpl', [])
                      ->will($this->returnValue('render_template'));
    $this->app['main\models\queries'] = $model;
    $response = $this->controller->stats($this->app);
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

  public function test_update_contacts_1(){
    $this->request->query->set('checked', 'false');
    $response = $this->controller->update_contacts($this->request, $this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_update_contacts_2(){
    $this->request->query->set('checked', 'true');
    $this->request->query->set('id', 125);
    $this->request->query->set('telephone', '647957');
    $this->request->query->set('cellphone', '+79222944742');
    $model = $this->getMockBuilder('main\models\queries')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('update_contacts')
          ->with(125, '647957', '+79222944742');
    $this->app['main\models\queries'] = $model;
    $response = $this->controller->update_contacts($this->request, $this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_update_query_type(){
    $this->request->query->set('id', 125);
    $this->request->query->set('type', 253);
    $query_type = new query_type();
    $query = $this->getMock('domain\query');
    $query->expects($this->once())
          ->method('set_query_type')
          ->with($query_type);
    $this->app['em']->expects($this->exactly(2))
                    ->method('find')
                    ->withConsecutive(['domain\query', 125], ['domain\query_type', 253])
                    ->will($this->onConsecutiveCalls($query, $query_type));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\update_query_type.tpl', ['query' => $query])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_query_type($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_work_type(){
    $this->request->query->set('id', 125);
    $this->request->query->set('type', 253);
    $workgroup = new workgroup();
    $query = $this->getMock('domain\query');
    $query->expects($this->once())
          ->method('add_work_type')
          ->with($workgroup);
    $this->app['em']->expects($this->exactly(2))
                    ->method('find')
                    ->withConsecutive(['domain\query', 125], ['domain\workgroup', 253])
                    ->will($this->onConsecutiveCalls($query, $workgroup));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('query\update_work_type.tpl', ['query' => $query])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->update_work_type($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }
}