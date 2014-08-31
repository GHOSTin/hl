<?php


/**
 * Class controller_task
 */
class controller_task {

  /**
   * @var string
   */
  static $name = 'Задачи';
  static $rules = [];

  public static function private_show_default_page(model_request $request){
    return true;
  }

  public static function private_get_dialog_create_task(model_request $request){
    return ['users' => di::get('em')->getRepository('data_user')->findAll()];
  }

  public static function private_add_task(model_request $request){
    $em = di::get('em');
    $task = new data_task();
    $task->set_id($em->getRepository('data_task')->getInsertId());
    $task->set_title($request->GET('title'));
    $task->set_description($request->GET('description'));
    $task->set_time_target($request->GET('time_target'));
    $task->set_time_open(time());
    $task->set_status('open');
    $users = new \Doctrine\Common\Collections\ArrayCollection();
    $user = new data_task2user();
    $user->set_user_type('creator');
    $user->set_task($task);
    $user->set_user(di::get('user'));
    $users->add($user);
    foreach($request->GET('performers') as $performer){
      $user = new data_task2user();
      $user->set_user_type('performer');
      $user->set_user($em->find('data_user', $performer));
      $user->set_task($task);
      $users->add($user);
    }
    $task->set_users($users);
    $em->persist($task);
    $em->flush();
    return ['tasks' => di::get('em')->getRepository('data_task')->findActiveTask()];
  }

  public static function private_close_task(model_request $request){
    $em = di::get('em');
    /** @var $task data_task */
    $task = $em->find('data_task', $request->GET('id'));
    $task->set_reason($request->GET('reason'));
    $task->set_rating(((int) substr($request->GET('rating'), -1))+ 1);
    $task->set_time_close((int) $request->GET('time_close'));
    $task->set_status('close');
    $em->flush($task);
    return ['tasks'=>di::get('em')->getRepository('data_task')->findActiveTask()];
  }

  public static function private_show_active_tasks(model_request $request){
    return ['tasks' => di::get('em')->getRepository('data_task')->findActiveTask()];
  }

  public static function private_show_finished_tasks(model_request $request){
    return ['tasks' => di::get('em')->getRepository('data_task')->findCloseTask()];
  }

  public static function private_get_task_content(model_request $request){
    return ['task'=> di::get('em')->find('data_task', $request->GET('id'))];
  }

  public static function private_edit_task_content(model_request $request){
    return ['task'=> di::get('em')->find('data_task', $request->GET('id')), 'users' => di::get('em')->getRepository('data_user')->findAll()];
  }

  public static function private_save_task_content(model_request $request){
    /** @var $em \Doctrine\ORM\EntityManager */
    /** @var $task data_task */
    $em = di::get('em');
    $task = $em->find('data_task', $request->GET('id'));
    $task->set_title($request->GET('title'));
    $task->set_description($request->GET('description'));
    $task->set_time_target((int) $request->GET('time_target'));
    if ($request->GET('status') == 'close'){
      $task->set_reason($request->GET('reason'));
      $task->set_rating(((int) substr($request->GET('rating'), -1))+ 1);
      $task->set_time_close((int) $request->GET('time_close'));
    }
    foreach ($task->get_users() as $user) {
      $em->remove($user);
    }
    $users = new \Doctrine\Common\Collections\ArrayCollection();
    $user = new data_task2user();
    $user->set_user_type('creator');
    $user->set_task($task);
    $user->set_user(di::get('user'));
    $users->add($user);
    foreach($request->GET('performers') as $performer){
      $user = new data_task2user();
      $user->set_user_type('performer');
      $user->set_user($em->find('data_user', $performer));
      $user->set_task($task);
      $users->add($user);
    }
    $task->set_users($users);
    $em->flush($task);
    return ['task'=> $task];
  }

  public static function private_get_dialog_close_task(model_request $request){
    return ['task'=> di::get('em')->find('data_task', $request->GET('id'))];
  }

  public static function private_send_task_comment(model_request $request){
    /** @var $em \Doctrine\ORM\EntityManager */
    /** @var $task data_task */
    $em = di::get('em');
    $task = $em->find('data_task', $request->GET('id'));
    $comment = new data_task2comment();
    $comment->set_task($task);
    $comment->set_user(di::get('user'));
    $comment->set_message($request->GET('message'));
    $comment->set_time(time());
    $em->persist($comment);
    $em->flush($comment);
    return ['comments'=>  $task->get_comments()];
  }
}