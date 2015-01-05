<?php namespace main\controllers;

use \RuntimeException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use \domain\query2user;
use \domain\query2comment;
use \domain\query2work;
use \domain\query;

class queries{

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

  public function add_user(Request $request, Application $app){
    $user = $app['em']->find('\domain\user', $request->get('user_id'));
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(in_array($request->get('type'),  ['manager', 'performer'], true)){
      $u = new query2user($query, $user);
      $u->set_class($request->get('type'));
      $app['em']->persist($u);
      $app['em']->flush();
    }else
      throw new RuntimeException('Несоответствующие параметры: class.');
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
    $model = $app['\main\models\query'];
    $model->init_default_params();
    return $app['twig']->render('query\clear_filters.tpl',
                                ['queries' => $model->get_queries(), 'timeline' => $model->get_timeline()]);
  }

  public function close_query(Request $request, Application $app){
    preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u',
      $request->get('reason'), $matches);
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    if(!in_array($query->get_status(), ['working', 'open'], true))
      throw new RuntimeException('Заявка не может быть закрыта.');
    $query->set_status('close');
    $query->set_close_reason(implode('', $matches[0]));
    $query->set_time_close(time());
    $app['em']->flush();
    return $app['twig']->render('query\get_query_content.tpl',
                                ['query' => $query]);
  }

  public function create_query(Request $request, Application $app){
    preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u',
      $request->get('description'), $matches);
    $time = getdate();
    $query = new query();
    $query->set_contact_fio($request->get('fio'));
    $query->set_contact_telephone($request->get('telephone'));
    $query->set_contact_cellphone($request->get('cellphone'));
    $query->set_description(implode('', $matches[0]));
    $query->set_initiator($request->get('initiator'));
    $query->set_status('open');
    $query->set_payment_status('unpaid');
    $query->set_warning_status('normal');
    $query->set_time_open($time[0]);
    $query->set_time_work($time[0]);
    if($request->get('initiator') === 'house'){
      $house = $app['em']->find('\domain\house', $request->get('id'));
    }elseif($request->get('initiator') === 'number'){
      $number = $app['em']->find('\domain\number', $request->get('id'));
      $query->add_number($number);
      $house = $number->get_flat()->get_house();
    }
    $query->set_house($house);
    $query->set_department($house->get_department());
    $query->add_work_type($app['em']->find('\domain\workgroup',
                                           $request->get('work_type')));
    $conn = $app['em']->getConnection();
    $q = $conn->query('SELECT MAX(querynumber) as number FROM `queries`
                    WHERE `opentime` > '.mktime(0, 0, 0, 1, 1, $time['year']).'
                AND `opentime` <= '.mktime(23, 59, 59, 23, 59, $time['year']));
    $query->set_number($q->fetch()['number'] + 1);
    $app['em']->persist($query);
    $app['em']->flush();
    $creator = new query2user($query, $app['user']);
    $creator->set_class('creator');
    $manager = new query2user($query, $app['user']);
    $manager->set_class('manager');
    $app['em']->persist($creator);
    $app['em']->persist($manager);
    $app['em']->flush();
    $params['time_begin'] = mktime(0, 0, 0,
                                   $time['mon'], $time['mday'], $time['year']);
    $params['time_end'] = mktime(23, 59, 59,
                                 $time['mon'], $time['mday'], $time['year']);
    $params['status'] = ['open', 'close', 'reopen', 'working'];
    $queries = $app['em']->getRepository('\domain\query')
                         ->findByParams($params);
    return $app['twig']->render('query\query_titles.tpl',
                                ['queries' => $queries]);
  }

  public function change_initiator(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('query_id'));
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
    return $app['twig']->render('query\get_query_content.tpl',
                                ['query' => $query]);
  }

  public function default_page(Request $request, Application $app){
    $model = $app['\main\models\query'];
    $types = $app['em']->getRepository('\domain\workgroup')->findBy([], ['name' => 'ASC']);
    $params = $model->get_params();
    if(!empty($params['houses']))
      $houses = $app['em']->getRepository('\domain\house')->findByid($params['houses'], ['number' => 'ASC']);
    else
      $houses = [];
    $profile = $app['user']->get_profile('query');
    if($request->get('id')){
      $queries = [$app['em']->find('\domain\query', $request->get('id'))];

    }else
      $queries = $model->get_queries();
    $time = getdate($model->get_timeline());
    $day = mktime(12, 0, 0, $time['mon'], 1, $time['year']);
    return $app['twig']->render('query\default_page.tpl',
                                ['user' => $app['user'], 'day' => $day,
                                 'queries' => $queries, 'params' => $model->get_filter_values(),
                                 'timeline' => $model->get_timeline(), 'streets' => $model->get_streets(),
                                 'departments' => $model->get_departments(), 'query_work_types' => $types,
                                 'houses' => $houses, 'rules' => $profile->get_rules()]);
  }

  public function get_day(Request $request, Application $app){
    $model = $app['\main\models\query'];
    $model->set_time($request->get('time'));
    return $app['twig']->render('query\query_titles.tpl',
                                ['queries' => $model->get_queries()]);
  }

  public function get_dialog_add_comment(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_add_comment.tpl',
                                ['query' => $query]);
  }

  public function get_dialog_add_user(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    $groups = $app['em']->getRepository('\domain\group')->findAll();
    return $app['twig']->render('query\get_dialog_add_user.tpl',
                                ['query' => $query, 'groups' => $groups,
                                'type' => $request->get('type')]);
  }

  public function get_dialog_add_work(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_add_work.tpl',
                                ['query' => $query]);
  }

  public function get_dialog_change_initiator(Request $request,
                                              Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    $streets = $app['em']->getRepository('\domain\street')
                         ->findBy([], ['name' => 'ASC']);
    return $app['twig']->render('query\get_dialog_change_initiator.tpl',
                                ['query' => $query, 'streets' => $streets]);
  }

  public function get_dialog_create_query(Application $app){
    return $app['twig']->render('query\get_dialog_create_query.tpl');
  }

  public function get_dialog_edit_contact_information(Request $request,
                                                      Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_edit_contact_information.tpl',
                                ['query' => $query]);
  }

  public function get_dialog_edit_description(Request $request,
                                              Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_edit_description.tpl',
                                ['query' => $query]);
  }

  public function get_dialog_edit_payment_status(Request $request,
                                                 Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_edit_payment_status.tpl',
                                ['query' => $query]);
  }

  public function get_dialog_edit_reason(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_edit_reason.tpl',
                                ['query' => $query]);
  }

  public function get_dialog_initiator(Request $request, Application $app){
    $streets = $app['em']->getRepository('\domain\street')
                         ->findBy([], ['name' => 'ASC']);
    return $app['twig']->render('query\get_dialog_initiator.tpl',
                                ['streets' => $streets,
                                 'value' => $request->get('value')]);
  }

  public function get_dialog_reclose_query(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_reclose_query.tpl',
                                ['query' => $query]);
  }

  public function get_dialog_reopen_query(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_reopen_query.tpl',
                                ['query' => $query]);
  }

  public function get_dialog_remove_user(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    $user = $app['em']->getRepository('\domain\user')
                      ->find($request->get('user_id'));
    return $app['twig']->render('query\get_dialog_remove_user.tpl',
                                ['query' => $query, 'user' => $user,
                                 'type' => $request->get('type')]);
  }

  public function get_dialog_remove_work(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    $work = $app['em']->find('\domain\work', $request->get('work_id'));
    return $app['twig']->render('query\get_dialog_remove_work.tpl',
                                ['query' => $query, 'work' => $work]);
  }

  public function get_dialog_edit_warning_status(Request $request,
                                                 Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_edit_warning_status.tpl',
                                ['query' => $query]);
  }

  public function get_dialog_edit_work_type(Request $request, Application $app){
    $types = $app['em']->getRepository('\domain\workgroup')
                       ->findBy([], ['name' => 'ASC']);
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_edit_work_type.tpl',
                                ['query' => $query, 'work_types' => $types]);
  }

  public function get_dialog_close_query(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_close_query.tpl',
                                ['query' => $query]);
  }

  public function get_dialog_to_working_query(Request $request,
                                              Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_dialog_to_working_query.tpl',
                                ['query' => $query]);
  }

  public function get_initiator(Request $request, Application $app){
    $types = $app['em']->getRepository('\domain\workgroup')
                       ->findBy([], ['name'=> 'ASC']);
    switch($request->get('initiator')){
      case 'number':
        $house = null;
        $number = $app['em']->find('\domain\number', $request->get('id'));
        $queries = $app['em']->getRepository('\domain\query')
          ->findByHouse($number->get_flat()->get_house()->get_id(),
                        ['id' => 'DESC'], 5);
      break;
      case 'house':
        $number = null;
        $house = $app['em']->find('\domain\house', $request->get('id'));
        $queries = $app['em']->getRepository('\domain\query')
                             ->findByHouse($house->get_id(),
                                          ['id' => 'DESC'], 5);
      break;
      default:
        throw new RuntimeException('Проблема типа инициатора.');
    }
    return $app['twig']->render('query\get_initiator.tpl',
                                ['query_work_types' => $types,
                                 'queries' => $queries,
                                 'number' => $number, 'house' => $house,
                                 'initiator' => $request->get('initiator')]);
  }

  public function get_query_comments(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_query_comments.tpl',
                                ['query' => $query]);
  }

  public function get_query_content(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_query_content.tpl',
                                ['query' => $query]);
  }

  public function get_documents(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_documents.tpl',
                                ['query' => $query]);
  }

  public function get_query_numbers(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_query_numbers.tpl',
                                ['query' => $query]);
  }

  public function get_query_title(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_query_title.tpl',
                                ['query' => $query]);
  }

  public function get_query_users(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_query_users.tpl',
                                ['query' => $query]);
  }

  public function get_query_works(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\get_query_works.tpl',
                                ['query' => $query]);
  }

  public function get_houses(Request $request, Application $app){
    $houses = $app['\main\models\query']
              ->get_houses_by_street($request->get('id'));
    return $app['twig']->render('query\get_houses.tpl', ['houses' => $houses]);
  }

  public function get_numbers(Request $request, Application $app){
    $house = $app['em']->find('\domain\house', $request->get('id'));
    return $app['twig']->render('query\get_numbers.tpl', ['house' => $house]);
  }

  public function get_search(Application $app){
    return $app['twig']->render('query\get_search.tpl');
  }

  public function get_search_result(Request $request, Application $app){
    $queries = $app['em']->getRepository('\domain\query')
                         ->findByNumber($request->get('param'));
    return $app['twig']->render('query\query_titles.tpl',
                                ['queries' => $queries]);
  }

  public function get_timeline(Request $request, Application $app){
    $model = $app['\main\models\query'];
    if($request->get('act') === 'next')
      $model->set_time(strtotime('noon first day of next month', $request->get('time')));
    else
      $model->set_time(strtotime('noon last day of previous month', $request->get('time')));
    return $app['twig']->render('query\get_timeline.tpl',
                                ['queries' => $model->get_queries(), 'timeline' => $model->get_timeline()]);
  }

  public function get_user_options(Request $request, Application $app){
    $group = $app['em']->find('\domain\group', $request->get('id'));
    return $app['twig']->render('query\get_user_options.tpl',
                                ['group' => $group]);
  }

  public function print_query(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    return $app['twig']->render('query\print_query.tpl', ['query' => $query]);
  }

  public function reclose_query(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    if($query->get_status() !== 'reopen')
      throw new RuntimeException();
    $query->set_status('close');
    $app['em']->flush();
    return $app['twig']->render('query\get_query_content.tpl',
                                ['query' => $query]);
  }

  public function reopen_query(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    if($query->get_status() !== 'close')
      throw new RuntimeException();
    $query->set_status('reopen');
    $app['em']->flush();
    return $app['twig']->render('query\get_query_content.tpl',
                                ['query' => $query]);
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

  public function set_department(Request $request, Application $app){
    $model = $app['\main\models\query'];
    $model->set_department($request->get('value'));
    $queries = $model->get_queries();
    return $app['twig']->render('query\query_titles.tpl',
                                ['queries' => $queries]);
  }

  public function set_house(Request $request, Application $app){
    $model = $app['\main\models\query'];
    $model->set_house($request->get('value'));
    $queries = $model->get_queries();
    return $app['twig']->render('query\query_titles.tpl',
                                ['queries' => $queries]);
  }

  public function set_status(Request $request, Application $app){
    $model = $app['\main\models\query'];
    $model->set_status($request->get('value'));
    $queries = $model->get_queries();
    return $app['twig']->render('query\set_status.tpl',
                                ['queries' => $queries]);
  }

  public function set_street(Request $request, Application $app){
    $model = $app['\main\models\query'];
    $model->set_street($request->get('value'));
    $houses = $model->get_houses_by_street($request->get('value'));
    $queries = $model->get_queries();
    return $app['twig']->render('query\set_street.tpl',
                                ['queries' => $queries, 'houses' => $houses]);
  }

  public function set_work_type(Request $request, Application $app){
    $model = $app['\main\models\query'];
    $model->set_work_type($request->get('value'));
    $queries = $model->get_queries();
    return $app['twig']->render('query\query_titles.tpl',
                                ['queries' => $queries]);
  }

  public function to_working_query(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    if($query->get_status() !== 'open')
      throw new RuntimeException();
    $query->set_status('working');
    $query->set_time_work(time());
    $app['em']->flush();
    return $app['twig']->render('query\get_query_content.tpl',
                                ['query' => $query]);
  }

  public function update_contact_information(Request $request,
                                             Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $query->set_contact_fio($request->get('fio'));
    $query->set_contact_telephone($request->get('telephone'));
    $query->set_contact_cellphone($request->get('cellphone'));
    $app['em']->flush();
    return $app['twig']->render('query\update_contact_information.tpl',
                                ['query' => $query]);
  }

  public function update_description(Request $request, Application $app){
    preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u',
                   $request->get('description'), $matches);
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $query->set_description(implode('', $matches[0]));
    $app['em']->flush();
    return $app['twig']->render('query\update_description.tpl',
                                ['query' => $query]);
  }

  public function update_payment_status(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $query->set_payment_status($request->get('status'));
    $app['em']->flush();
    return $app['twig']->render('query\update_payment_status.tpl',
                                ['query' => $query]);
  }

  public function update_reason(Request $request, Application $app){
    preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u',
                   $request->get('reason'), $matches);
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $query->set_close_reason(implode('', $matches[0]));
    $app['em']->flush();
    return $app['twig']->render('query\update_reason.tpl',
                                ['query' => $query]);
  }

  public function update_work_type(Request $request, Application $app){
    $type = $app['em']->find('\domain\workgroup', $request->get('type'));
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $query->add_work_type($type);
    $app['em']->flush();
    return $app['twig']->render('query\update_work_type.tpl',
                                ['query' => $query]);
  }

  public function update_warning_status(Request $request, Application $app){
    $query = $app['em']->find('\domain\query', $request->get('id'));
    if(is_null($query))
      throw new RuntimeException();
    $query->set_warning_status($request->get('status'));
    $app['em']->flush();
    return $app['twig']->render('query\update_warning_status.tpl',
                                ['query' => $query]);
  }
}