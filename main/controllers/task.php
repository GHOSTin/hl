<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \domain\task2comment;
use \domain\task2user;

class task{

  public function add_task(Request $request, Application $app){
    $task = new \domain\task();
    $task->set_id($app['em']->getRepository('\domain\task')->getInsertId());
    $task->set_title($request->get('title'));
    $task->set_description($request->get('description'));
    $task->set_time_target($request->get('time_target'));
    $task->set_time_open(time());
    $task->set_status('open');
    $users = new \Doctrine\Common\Collections\ArrayCollection();
    $user = new task2user();
    $user->set_userType('creator');
    $user->set_task($task);
    $user->set_user($app['user']);
    $users->add($user);
    foreach($request->get('performers') as $performer){
      $user = new task2user();
      $user->set_userType('performer');
      $user->set_user($app['em']->find('\domain\user', $performer));
      $user->set_task($task);
      $users->add($user);
    }
    $task->set_users($users);
    $app['em']->persist($task);
    $app['em']->flush();
    $tasks = $app['em']->getRepository('\domain\task')->findActiveTask();
    return $app['twig']->render('task\show_active_tasks.tpl',
                                ['tasks' => $tasks]);
  }

  public function close_task(Request $request, Application $app){
    $task = $app['em']->find('\domain\task', $request->get('id'));
    $task->set_reason($request->get('reason'));
    $task->set_rating(((int) substr($request->get('rating'), -1))+ 1);
    $task->set_time_close((int) $request->get('time_close'));
    $task->set_status('close');
    $app['em']->flush($task);
    $tasks = $app['em']->getRepository('\domain\task')->findActiveTask();
    return $app['twig']->render('task\close_task.tpl',
                                ['tasks' => $tasks]);
  }

  public function edit_task_content(Request $request, Application $app){
    $task = $app['em']->find('\domain\task', $request->get('id'));
    $users = $app['em']->getRepository('\domain\user')->findAll();
    return $app['twig']->render('task\edit_task_content.tpl',
                                ['users' => $users, 'task' => $task]);
  }

  public function default_page(Application $app){
    return $app['twig']->render('task\default_page.tpl',
                                ['user' => $app['user']]);
  }

  public function get_dialog_create_task(Request $request, Application $app){
    $users = $app['em']->getRepository('\domain\user')->findAll();
    return $app['twig']->render('task\get_dialog_create_task.tpl',
                                ['users' => $users]);
  }

  public function get_dialog_close_task(Request $request, Application $app){
    $task = $app['em']->find('\domain\task', $request->get('id'));
    return $app['twig']->render('task\get_dialog_close_task.tpl',
                                ['task' => $task]);
  }

  public function get_task_content(Request $request, Application $app){
    $task = $app['em']->find('\domain\task', $request->get('id'));
    return $app['twig']->render('task\get_task_content.tpl',
                                ['user' => $app['user'], 'task' => $task]);
  }

  public function save_task_content(Request $request, Application $app){
    $task = $app['em']->find('\domain\task', $request->get('id'));
    $task->set_title($request->get('title'));
    $task->set_description($request->get('description'));
    $task->set_time_target((int) $request->get('time_target'));
    if ($request->get('status') == 'close'){
      $task->set_reason($request->get('reason'));
      $task->set_rating(((int) substr($request->get('rating'), -1))+ 1);
      $task->set_time_close((int) $request->get('time_close'));
    }
    foreach ($task->get_users() as $user) {
      $app['em']->remove($user);
    }
    $users = new \Doctrine\Common\Collections\ArrayCollection();
    $user = new task2user();
    $user->set_userType('creator');
    $user->set_task($task);
    $user->set_user($app['user']);
    $users->add($user);
    foreach($request->get('performers') as $performer){
      $user = new task2user();
      $user->set_userType('performer');
      $user->set_user($app['em']->find('\domain\user', $performer));
      $user->set_task($task);
      $users->add($user);
    }
    $task->set_users($users);
    $app['em']->flush($task);
    return $app['twig']->render('task\save_task_content.tpl',
                                ['user' => $app['user'], 'task' => $task]);
  }

  public function send_task_comment(Request $request, Application $app){
    $task = $app['em']->find('\domain\task', $request->get('id'));
    $comment = new task2comment();
    $comment->set_task($task);
    $comment->set_user($app['user']);
    $comment->set_message($request->get('message'));
    $comment->set_time(time());
    $app['em']->persist($comment);
    $app['em']->flush($comment);
    $comments = $task->get_comments();
    return $app['twig']->render('task\send_task_comment.tpl',
                                ['comments' => $comments]);
  }

  public function show_active_tasks(Application $app){
    $tasks = $app['em']->getRepository('\domain\task')->findActiveTask();
    return $app['twig']->render('task\show_active_tasks.tpl',
                                ['tasks' => $tasks]);
  }

  public function show_finished_tasks(Application $app){
    $tasks = $app['em']->getRepository('\domain\task')->findCloseTask();
    return $app['twig']->render('task\show_finished_tasks.tpl',
                                ['tasks' => $tasks]);
  }
}