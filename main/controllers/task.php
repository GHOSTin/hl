<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use domain\task2comment;

class task{

  public function add_task(Request $request, Application $app){
    $id = $app['em']->getRepository('\domain\task')
                    ->getInsertId();
    $task = new \domain\task($id,
                           $app['user'],
                           $request->get('title'),
                           $request->get('description'),
                           $request->get('time_target'),
                           time()
                          );
    $users = $app['em']->getRepository('domain\user')
                       ->findById($request->get('performers'));
    foreach($users as $user){
      $task->add_performer($user);
    }
    $app['em']->persist($task);
    $app['em']->flush();
    $tasks = $app['em']->getRepository('domain\task')
                       ->findActiveTask($app['user']);
    return $app['twig']->render('task\show_active_tasks.tpl', ['tasks' => $tasks]);
  }

  public function close_task(Request $request, Application $app){
    $task = $app['em']->find('\domain\task', $request->get('id'));
    $task->close_task($request->get('reason'), $request->get('time_close'), $request->get('rating'));
    $app['em']->flush($task);
    $tasks = $app['em']->getRepository('\domain\task')
                       ->findActiveTask($app['user']);
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
                                [
                                  'user' => $app['user'],
                                 'task' => $task]);
  }

  public function save_task_content(Request $request, Application $app){
    $task = $app['em']->find('domain\task', $request->get('id'));
    if($task->get_status() !== 'close'){
      $reason = null;
      $time_close = null;
      $rating = 0;
      die('345');
    }else{
      $reason = $request->get('reason');
      $time_close = $request->get('time_close');
      $rating = $request->get('rating');
    }
    $task->update($request->get('title'),
                  $request->get('description'),
                  $reason,
                  $request->get('time_target'),
                  $time_close,
                  $rating);
    $task->get_performers()->clear();
    $users = $app['em']->getRepository('domain\user')
                       ->findById($request->get('performers'));
    foreach($users as $user){
      $task->add_performer($user);
    }
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
    $tasks = $app['em']->getRepository('\domain\task')
                       ->findActiveTask($app['user']);
    return $app['twig']->render('task\show_active_tasks.tpl',
                                ['tasks' => $tasks]);
  }

  public function show_finished_tasks(Application $app){
    $tasks = $app['em']->getRepository('\domain\task')->findCloseTask($app['user']);
    return $app['twig']->render('task\show_finished_tasks.tpl',
                                ['tasks' => $tasks]);
  }
}