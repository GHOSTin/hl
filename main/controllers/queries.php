<?php namespace main\controllers;

use RuntimeException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use domain\query2user;
use domain\query2comment;
use domain\query2work;
use domain\query;
use domain\file;
use domain\query2file;

class queries{

  const RE_DESCRIPTION = '|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u';

  public function abort_query_from_request(Request $request, Application $app){
     preg_match_all(self::RE_DESCRIPTION, $request->get('description'), $matches);
     return $app['main\models\queries']->abort_query_from_request(
                                           implode('', $matches[0]),
                                           $request->get('time'),
                                           $request->get('work_type'),
                                           $request->get('query_type'),
                                           $request->get('number')
                                         );
  }

  public function abort_query_from_request_dialog(Request $request, Application $app){
    return $app['main\models\queries']->abort_query_from_request_dialog(
                                          $request->get('number'),
                                          $request->get('time')
                                        );
  }

  public function create_query_from_request_dialog(Request $request, Application $app){
    return $app['main\models\queries']->create_query_from_request_dialog(
                                          $request->get('number'),
                                          $request->get('time')
                                        );
  }

 public function create_query_from_request(Request $request, Application $app){
    preg_match_all(self::RE_DESCRIPTION, $request->get('description'), $matches);
    return $app['main\models\queries']->create_query_from_request(
                                          implode('', $matches[0]),
                                          $request->get('time'),
                                          $request->get('work_type'),
                                          $request->get('query_type'),
                                          $request->get('number')
                                        );
  }

  public function add_comment(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('query_id'));
    $comment = $app['\domain\query2comment'];
    $comment->set_user($app['user']);
    $comment->set_query($query);
    $comment->set_time(time());
    $comment->set_message($request->get('message'));
    $query->add_comment($comment);
    $app['em']->persist($comment);
    $app['em']->flush();
    return $app['twig']->render('query\add_comment.tpl', ['query' => $query]);
  }

  public function add_file(Request $request, Application $app, $id){
    $query = $app['em']->find('domain\query', $id);
    $file = $request->files->get('file');
    if($file->isValid()) {
      $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
      $time = time();
      $name = sha1(implode('_', [$time, $app['session']->getId(), rand(0, 100000)]));
      $path = date('Ymd').'/'.$name.'.'.$ext;
      $stream = fopen($file->getRealPath(), 'r+');
      $app['filesystem']->writeStream($path, $stream);
      fclose($stream);
      $file = new file($app['user'], $path, $time, $file->getClientOriginalName());
      $query->add_file($file);
      $app['em']->flush();
    }
    return $app['twig']->render('query\query_files.tpl', ['query' => $query]);
  }

  public function add_user(Request $request, Application $app){
    $user = $app['em']->find('\domain\user', $request->get('user_id'));
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(in_array($request->get('type'),  ['manager', 'performer'], true)){
      $q2u = new query2user($query, $user);
      $q2u->set_class($request->get('type'));
      $app['em']->persist($q2u);
      $app['em']->flush();
    }else
      throw new RuntimeException('Несоответствующие параметры: class '.$request->get('type'));
    return $app['twig']->render('query\add_user.tpl', ['query' => $query]);
  }

  public function add_work(Request $request, Application $app){
    $begin_hours = (int) $request->get('begin_hours');
    $begin_minutes = (int) $request->get('begin_minutes');
    $begin_date = (string) $request->get('begin_date');
    $end_hours = (int) $request->get('end_hours');
    $end_minutes = (int) $request->get('end_minutes');
    $end_date = (string) $request->get('end_date');
    $begin = strtotime($begin_hours.':'.$begin_minutes.' '.$begin_date);
    $end = strtotime($end_hours.':'.$end_minutes.' '.$end_date);
    if($begin > $end)
      throw new RuntimeException('wrong time.');
    $query = $app['em']->find('\domain\query', $request->get('id'));
    $w = $app['em']->find('\domain\work', $request->get('work_id'));
    $work = new query2work($query, $w);
    $work->set_time_open($begin);
    $work->set_time_close($end);
    $app['em']->persist($work);
    $app['em']->flush();
    return $app['twig']->render('query\add_work.tpl', ['query' => $query]);
  }

  public function clear_filters(Application $app){
    $model = $app['main\models\queries'];
    $model->init_default_params();
    return $app['twig']->render('query\clear_filters.tpl',
                                [
                                 'queries'  => $model->get_queries(),
                                 'timeline' => $model->get_timeline()
                                ]);
  }

  public function close_query(Request $request, Application $app){
    preg_match_all(self::RE_DESCRIPTION, $request->get('reason'), $matches);
    $query = $app['em']->find('domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $query->close($app['user'], time(), implode('', $matches[0]));
    $app['em']->flush();
    return $app['twig']->render('query\get_query_content.tpl', ['query' => $query]);
  }

  public function history(Application $app, $id){
    $query = $app['em']->find('domain\query', $id);
    if(is_null($query))
      throw new RuntimeException();
    return $app['twig']->render('query\history.tpl', ['query' => $query, 'user' => $app['user']]);
  }

  public function create_query(Request $request, Application $app){
    preg_match_all(self::RE_DESCRIPTION, $request->get('description'), $matches);
    return $app['main\models\queries']->create_query(
                                          implode('', $matches[0]),
                                          $request->get('initiator'),
                                          $request->get('work_type'),
                                          $request->get('query_type'),
                                          $request->get('fio'),
                                          $request->get('telephone'),
                                          $request->get('cellphone'),
                                          $request->get('id')
                                        );
  }

  public function change_initiator(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('query_id'));
    if(!is_null($query->get_request()))
      throw new RuntimeException();
    $numbers = $query->get_numbers()->clear();
    if(!is_null($request->get('number_id'))){
      $query->set_initiator('number');
      $number = $app['em']->find('\domain\number', $request->get('number_id'));
      $query->set_house($number->get_flat()->get_house());
      $query->add_number($number);
    }elseif(!is_null($request->get('house_id'))){
      $query->set_initiator('house');
      $house = $app['em']->find('\domain\house', $request->get('house_id'));
      $query->set_house($house);
    }else
      throw new RuntimeException('initiator wrong');
    $app['em']->flush();
    return $app['twig']->render('query\get_query_content.tpl', ['query' => $query]);
  }

  public function default_page(Request $request, Application $app){
    $model  = $app['main\models\queries'];
    $query_types  = $app['em']->getRepository('domain\query_type')
                              ->findAll(['name' => 'ASC']);
    $params = $model->get_params();
    if(!empty($params['houses']))
      $houses = $app['em']->getRepository('domain\house')
                          ->findByid($params['houses'], ['number' => 'ASC']);
    else
      $houses = [];
    if($request->get('id')){
      $queries = [$app['em']->find('domain\query', $request->get('id'))];
    }else
      $queries = $model->get_queries();
    return $app['twig']->render('query\default_page.tpl',
                                [
                                 'user' => $app['user'],
                                 'queries' => $queries,
                                 'params' => $model->get_filter_values(),
                                 'timeline' => $model->get_timeline(),
                                 'streets' => $model->get_streets(),
                                 'departments' => $model->get_departments(),
                                 'query_work_types' => $model->get_categories(),
                                 'query_types' => $query_types,
                                 'houses' => $houses
                                ]);
  }

  public function delete_file(Application $app, $id, $date, $name){
    $query = $app['em']->find('domain\query', $id);
    $file = $app['em']->getRepository('domain\query2file')
                      ->findOneBy([
                                   'query' => $id,
                                   'file' => $date.'/'.$name
                                  ]);
    $query->delete_file($file);
    $path = $file->get_path();
    $app['em']->flush();
    $app['filesystem']->delete($path);
    return $app['twig']->render('query\query_files.tpl', ['query' => $query]);
  }

  public function stats(Application $app){
    $model = $app['main\models\queries'];
    return $app['twig']->render('query\stats.tpl', $model->get_day_stats());
  }

  public function get_day(Request $request, Application $app){
    $model = $app['main\models\queries'];
    $model->set_time($request->get('time'));
    return $app['twig']->render('query\query_titles.tpl', ['queries' => $model->get_queries()]);
  }

  public function get_dialog_add_comment(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_add_comment.tpl', ['query' => $query]);
  }

  public function edit_visible_dialog(Application $app, $id){
    $query = $app['em']->find('domain\query', $id);
    return $app['twig']->render('query\edit_visible_dialog.tpl', ['query' => $query]);
  }

  public function update_visible(Application $app, $id){
    $query = $app['em']->find('domain\query', $id);
    $query->update_visible();
    $app['em']->flush();
    return $app['twig']->render('query\get_query_content.tpl', ['query' => $query]);
  }

  public function get_dialog_add_user(Request $request, Application $app){
    $query  = $app['em']->find('\domain\query', $request->get('id'));
    $groups = $app['em']->getRepository('\domain\group')->findAll();
    return $app['twig']->render('query\get_dialog_add_user.tpl',
                                [
                                 'query'  => $query,
                                 'groups' => $groups,
                                 'type'   => $request->get('type')
                                ]);
  }

  public function get_dialog_add_work(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_add_work.tpl', ['query' => $query]);
  }

  public function get_dialog_change_initiator(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(!is_null($query->get_request()))
      throw new RuntimeException();
    $streets = $app['em']->getRepository('\domain\street')->findBy([], ['name' => 'ASC']);
    return $app['twig']->render('query\get_dialog_change_initiator.tpl',
                                [
                                 'query' => $query,
                                 'streets' => $streets
                                ]);
  }

  public function get_dialog_create_query(Application $app){
    return $app['twig']->render('query\get_dialog_create_query.tpl');
  }

  public function get_dialog_delete_file(Application $app, $id, $date, $name){
    $file = $app['em']->getRepository('domain\query2file')
                      ->findOneBy([
                                   'query' => $id,
                                   'file' => $date.'/'.$name
                                  ]);
    return $app['twig']->render('query\get_dialog_delete_file.tpl', ['file' => $file]);
  }

  public function get_dialog_edit_contact_information(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_edit_contact_information.tpl', ['query' => $query]);
  }

  public function get_dialog_edit_description(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_edit_description.tpl', ['query' => $query]);
  }

  public function get_dialog_edit_reason(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_edit_reason.tpl', ['query' => $query]);
  }

  public function get_dialog_initiator(Request $request, Application $app){
    $streets = $app['main\models\queries']->get_streets();
    return $app['twig']->render('query\get_dialog_initiator.tpl',
                                [
                                 'streets' => $streets,
                                 'value'   => $request->get('value')
                                ]);
  }

  public function get_dialog_reclose_query(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_reclose_query.tpl', ['query' => $query]);
  }

  public function get_dialog_reopen_query(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_reopen_query.tpl', ['query' => $query]);
  }

  public function get_dialog_remove_user(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    $user = $app['em']->getRepository('\domain\user')->find($request->get('user_id'));
    return $app['twig']->render('query\get_dialog_remove_user.tpl',
                                [
                                 'query' => $query,
                                 'user'  => $user,
                                 'type'  => $request->get('type')
                                ]);
  }

  public function get_dialog_remove_work(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    $work = $app['em']->find('\domain\work', $request->get('work_id'));
    return $app['twig']->render('query\get_dialog_remove_work.tpl',
                                [
                                 'query' => $query,
                                 'work' => $work
                                ]);
  }

  public function get_dialog_change_query_type(Request $request, Application $app){
    $query = $app['em']->find('domain\query', $request->get('id'));
    $query_types = $app['em']->getRepository('domain\query_type')->findAll(['name' => 'ASC']);
    return $app['twig']->render('query\get_dialog_change_query_type.tpl',
                                [
                                 'query' => $query,
                                 'query_types' => $query_types
                                ]);
  }

  public function get_dialog_edit_work_type(Request $request, Application $app){
    $types = $app['em']->getRepository('domain\workgroup')->findAll(['name' => 'ASC']);
    $query = $app['em']->find('domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_edit_work_type.tpl',
                                [
                                 'query' => $query,
                                 'work_types' => $types
                                ]);
  }

  public function get_dialog_close_query(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_close_query.tpl', ['query' => $query]);
  }

  public function get_dialog_to_working_query(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_to_working_query.tpl', ['query' => $query]);
  }

  public function get_file(Application $app, $id, $date, $name){
    $file = $app['em']->getRepository('domain\query2file')
                      ->findOneBy([
                                   'query' => $id,
                                   'file' => $date.'/'.$name
                                  ]);
    if($file && $app['filesystem']->has($file->get_path())){
      $response = new BinaryFileResponse($app['files'].$file->get_path());
      $disposition = $response->headers->makeDisposition(
                                          ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                                          $file->get_name(),
                                          iconv('UTF-8', 'ASCII//TRANSLIT', $file->get_name())
                                         );
      $response->headers->set('Content-Disposition', $disposition);
      return $response;
    }else
      throw new NotFoundHttpException();
  }

  public function get_initiator(Request $request, Application $app){
    $model  = $app['main\models\queries'];
    switch($request->get('initiator')){
      case 'number':
        $house = null;
        $number = $app['em']->find('\domain\number', $request->get('id'));
        $queries = $app['em']->getRepository('\domain\query')
                             ->findByHouse($number->get_flat()->get_house()->get_id(), ['id' => 'DESC'], 5);
      break;
      case 'house':
        $number = null;
        $house = $app['em']->find('\domain\house', $request->get('id'));
        $queries = $app['em']->getRepository('\domain\query')
                             ->findByHouse($house->get_id(), ['id' => 'DESC'], 5);
      break;
      default:
        throw new RuntimeException('Проблема типа инициатора.');
    }
    $query_types = $app['em']->getRepository('domain\query_type')->findAll();
    return $app['twig']->render('query\get_initiator.tpl',
                                [
                                 'query_work_types' => $model->get_categories(),
                                 'queries' => $queries,
                                 'number' => $number,
                                 'house' => $house,
                                 'initiator' => $request->get('initiator'),
                                 'query_types' => $query_types,
                                 'user' => $app['user']
                                ]);
  }

  public function get_query_comments(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_query_comments.tpl', ['query' => $query]);
  }

  public function get_query_content(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_query_content.tpl', ['query' => $query]);
  }

  public function get_query_files(Application $app, $id){
    $query = $app['em']->find('domain\query', $id);
    return $app['twig']->render('query\get_query_files.tpl', ['query' => $query]);
  }

  public function get_documents(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_documents.tpl', ['query' => $query]);
  }

  public function get_query_numbers(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_query_numbers.tpl', ['query' => $query]);
  }

  public function get_query_title(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_query_title.tpl', ['query' => $query]);
  }

  public function get_query_users(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_query_users.tpl', ['query' => $query]);
  }

  public function get_query_works(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_query_works.tpl', ['query' => $query]);
  }

  public function get_houses(Request $request, Application $app){
    $houses = $app['main\models\queries']->get_houses_by_street($request->get('id'));
    return $app['twig']->render('query\get_houses.tpl', ['houses' => $houses]);
  }

  public function get_numbers(Request $request, Application $app){
    $house = $app['em']->find('domain\house', $request->get('id'));
    return $app['twig']->render('query\get_numbers.tpl', ['house' => $house]);
  }

  public function get_search(Application $app){
    return $app['twig']->render('query\get_search.tpl');
  }

  public function get_search_result(Request $request, Application $app){
    $queries = $app['em']->getRepository('\domain\query')->findByNumber($request->get('param'));
    return $app['twig']->render('query\query_titles.tpl', ['queries' => $queries]);
  }

  public function get_timeline(Request $request, Application $app){
    $model = $app['main\models\queries'];
    if($request->get('act') === 'next')
      $model->set_time(strtotime('noon first day of next month', $request->get('time')));
    else
      $model->set_time(strtotime('noon last day of previous month', $request->get('time')));
    return $app['twig']->render('query\get_timeline.tpl',
                                [
                                 'queries'  => $model->get_queries(),
                                 'timeline' => $model->get_timeline()
                                ]);
  }

  public function get_user_options(Request $request, Application $app){
    $group = $app['em']->find('\domain\group', $request->get('id'));
    return $app['twig']->render('query\get_user_options.tpl', ['group' => $group]);
  }

  public function noclose(Request $request, Application $app){
    return $app['main\models\queries']->noclose($request->get('time'));
  }

  public function all_noclose(Application $app){
    return $app['main\models\queries']->all_noclose();
  }

  public function print_query(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\print_query.tpl', ['query' => $query]);
  }

  public function reclose_query(Request $request, Application $app){
    $query = $app['em']->find('domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $query->reclose($app['user']);
    $app['em']->flush();
    return $app['twig']->render('query\get_query_content.tpl', ['query' => $query]);
  }

  public function reopen_query(Request $request, Application $app){
    $query = $app['em']->find('domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $query->reopen($app['user']);
    $app['em']->flush();
    return $app['twig']->render('query\get_query_content.tpl', ['query' => $query]);
  }

  public function requests(Application $app){
    return $app['main\models\number_request']->requests();
  }

  public function count(Application $app){
    return $app['main\models\number_request']->count();
  }

  public function outages(Application $app){
    $response = $app['main\models\queries']->outages();
    return $app->json($response);
  }

  public function phrases(Application $app, $id){
    return $app['main\models\queries']->phrases($id);
  }

  public function remove_user(Request $request, Application $app){
    $u = $app['em']->find('\domain\user', $request->get('user_id'));
    $query = $app['em']->find('\domain\query', $request->get('id'));
    $users = $query->get_users();
    if(!empty($users))
      foreach($users as $user)
        if($user->get_id() === $u->get_id()
          && $user->get_class() === $request->get('type')){
          $app['em']->remove($user);
          $app['em']->flush();
        }
    return $app['twig']->render('query\add_user.tpl', ['query' => $query]);
  }

  public function remove_work(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    $work = $app['em']->find('\domain\work', $request->get('work_id'));
    $w = $query->remove_work($work);
    $app['em']->remove($w);
    $app['em']->flush();
    return $app['twig']->render('query\add_work.tpl', ['query' => $query]);
  }

  public function selections(Application $app){
    return $app['main\models\queries']->selections();
  }

  public function set_department(Request $request, Application $app){
    $model = $app['main\models\queries'];
    $model->set_department($request->get('value'));
    $queries = $model->get_queries();
    return $app['twig']->render('query\query_titles.tpl', ['queries' => $queries]);
  }

  public function set_house(Request $request, Application $app){
    $model = $app['main\models\queries'];
    $model->set_house($request->get('value'));
    $queries = $model->get_queries();
    return $app['twig']->render('query\query_titles.tpl', ['queries' => $queries]);
  }

  public function set_status(Request $request, Application $app){
    $model = $app['main\models\queries'];
    $model->set_status($request->get('value'));
    $queries = $model->get_queries();
    return $app['twig']->render('query\query_titles.tpl', ['queries' => $queries]);
  }

  public function set_street(Request $request, Application $app){
    $model = $app['main\models\queries'];
    $model->set_street($request->get('value'));
    $houses = $model->get_houses_by_street($request->get('value'));
    $queries = $model->get_queries();
    return $app['twig']->render('query\set_street.tpl', ['queries' => $queries, 'houses' => $houses]);
  }

  public function set_query_type(Request $request, Application $app){
    $model = $app['main\models\queries'];
    $model->set_query_type($request->get('value'));
    $queries = $model->get_queries();
    return $app['twig']->render('query\query_titles.tpl', ['queries' => $queries]);
  }

  public function set_work_type(Request $request, Application $app){
    $model = $app['main\models\queries'];
    $model->set_work_type($request->get('value'));
    $queries = $model->get_queries();
    return $app['twig']->render('query\query_titles.tpl', ['queries' => $queries]);
  }

  public function to_working_query(Request $request, Application $app){
    $query = $app['em']->find('domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $query->to_work(time());
    $app['em']->flush();
    return $app['twig']->render('query\get_query_content.tpl', ['query' => $query]);
  }

  public function update_contact_information(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $query->set_contact_fio($request->get('fio'));
    $query->set_contact_telephone($request->get('telephone'));
    $query->set_contact_cellphone($request->get('cellphone'));
    $app['em']->flush();
    return $app['twig']->render('query\update_contact_information.tpl', ['query' => $query]);
  }

  public function update_contacts(Request $request, Application $app){
    if($request->get('checked') === 'true'){
      $app['main\models\queries']->update_contacts(
                                            $request->get('id'),
                                            $request->get('telephone'),
                                            $request->get('cellphone')
                                          );
    }
    return new Response();
  }

  public function update_description(Request $request, Application $app){
    preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u', $request->get('description'), $matches);
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $context = [
      'Старое описание' => $query->get_description(),
      'Новое описание' => implode('', $matches[0])
    ];
    $query->set_description(implode('', $matches[0]));
    $query->add_history_event($app['user'], 'Изменение описания заявки', $context);
    $app['em']->flush();
    return $app['twig']->render('query\update_description.tpl', ['query' => $query]);
  }

  public function update_reason(Request $request, Application $app){
    preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u', $request->get('reason'), $matches);
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $query->set_close_reason(implode('', $matches[0]));
    $context = [
      'Причина закрытия' => implode('', $matches[0])
    ];
    $query->add_history_event($app['user'], 'Изменение причины закрытия', $context);
    $app['em']->flush();
    return $app['twig']->render('query\update_reason.tpl', ['query' => $query]);
  }

  public function update_query_type(Request $request, Application $app){
    $query = $app['em']->find('domain\query', $request->get('id'));
    $query_type = $app['em']->find('domain\query_type', $request->get('type'));
    $query->set_query_type($query_type);
    $app['em']->flush();
    return $app['twig']->render('query\update_query_type.tpl', ['query' => $query]);
  }

  public function update_work_type(Request $request, Application $app){
    $query = $app['em']->find('domain\query', $request->get('id'));
    $type = $app['em']->find('domain\workgroup', $request->get('type'));
    $query->add_work_type($type);
    $app['em']->flush();
    return $app['twig']->render('query\update_work_type.tpl', ['query' => $query]);
  }
}