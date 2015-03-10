<?php namespace main\controllers;

use RuntimeException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use domain\workgroup;
use domain\work;
use domain\event;

class works{

  public function add_work(Request $request, Application $app){
    $workgroup = $app['em']->getRepository('\domain\workgroup')
                           ->findOneById($request->get('workgroup_id'));
    $work = $app['em']->getRepository('\domain\work')
                      ->findOneById($request->get('work_id'));
    $workgroup->add_work($work);
    $app['em']->flush();
    return $app['twig']->render('works\get_workgroup_content.tpl', ['workgroup' => $workgroup]);
  }

  public function add_event(Request $request, Application $app){
    $workgroup = $app['em']->getRepository('domain\workgroup')
                           ->findOneById($request->get('workgroup_id'));
    $event = $app['em']->getRepository('domain\event')
                       ->findOneById($request->get('event_id'));
    $workgroup->add_event($event);
    $app['em']->flush();
    return $app['twig']->render('works\get_workgroup_content.tpl', ['workgroup' => $workgroup]);
  }

  public function create_event(Request $request, Application $app){
    $name = $request->get('name');
    $event = $app['em']->getRepository('domain\event')->findOneByName($name);
    if(!is_null($event))
      throw new RuntimeException('Такое событие существует.');
    $event = new event();
    $event->set_name($name);
    $app['em']->persist($event);
    $app['em']->flush();
    $events = $app['em']->getRepository('domain\event')->findAll();
    return $app['twig']->render('works\events.tpl', ['events' => $events]);
  }

  public function create_work(Request $request, Application $app){
    $name = $request->get('name');
    $work = $app['em']->getRepository('\domain\work')->findOneByName($name);
    if(!is_null($work))
      throw new RuntimeException('Такая группа существует.');
    $app['em']->persist(work::new_instance($name));
    $app['em']->flush();
    $works = $app['em']->getRepository('\domain\work')->findAll();
    return $app['twig']->render('works\works.tpl', ['works' => $works]);
  }

  public function create_workgroup(Request $request, Application $app){
    $name = $request->get('name');
    $workgroup = $app['em']->getRepository('\domain\workgroup')
                           ->findOneByName($name);
    if(!is_null($workgroup))
      throw new RuntimeException('Такая группа существует.');
    $app['em']->persist(workgroup::new_instance($name));
    $app['em']->flush();
    $workgroups = $app['em']->getRepository('\domain\workgroup')->findAll();
    return $app['twig']->render('works\workgroups.tpl', ['workgroups' => $workgroups]);
  }

  public function default_page(Request $request, Application $app){
    $groups = $app['em']->getRepository('domain\workgroup')->findAll();
    $works = $app['em']->getRepository('domain\work')->findAll();
    $events = $app['em']->getRepository('domain\event')->findAll();
    return $app['twig']->render('works\default_page.tpl',
                                [
                                 'user' => $app['user'],
                                 'workgroups' => $groups,
                                 'works' => $works,
                                 'events' => $events
                                ]);
  }

  public function exclude_event(Request $request, Application $app){
    $workgroup = $app['em']->getRepository('domain\workgroup')
                           ->findOneById($request->get('workgroup_id'));
    $event = $app['em']->getRepository('domain\event')
                      ->findOneById($request->get('event_id'));
    $workgroup->exclude_event($event);
    $app['em']->flush();
    return $app['twig']->render('works\get_workgroup_content.tpl', ['workgroup' => $workgroup]);
  }

  public function exclude_work(Request $request, Application $app){
    $workgroup = $app['em']->getRepository('\domain\workgroup')
                           ->findOneById($request->get('workgroup_id'));
    $work = $app['em']->getRepository('\domain\work')
                      ->findOneById($request->get('work_id'));
    $workgroup->exclude_work($work);
    $app['em']->flush();
    return $app['twig']->render('works\get_workgroup_content.tpl', ['workgroup' => $workgroup]);
  }

  public function get_dialog_add_event(Request $request, Application $app){
    $workgroup = $app['em']->getRepository('domain\workgroup')
                           ->findOneById($request->get('id'));
    $events = $app['em']->getRepository('domain\event')->findAll();
    return $app['twig']->render('works\get_dialog_add_event.tpl',
                                [
                                 'workgroup' => $workgroup,
                                 'events' => $events
                                ]);
  }

  public function get_dialog_add_work(Request $request, Application $app){
    $workgroup = $app['em']->getRepository('\domain\workgroup')
                           ->findOneById($request->get('id'));
    $works = $app['em']->getRepository('\domain\work')->findAll();
    return $app['twig']->render('works\get_dialog_add_work.tpl',
                                [
                                 'workgroup' => $workgroup,
                                 'works' => $works
                                ]);
  }

  public function get_dialog_create_event(Application $app){
    return $app['twig']->render('works\get_dialog_create_event.tpl');
  }

  public function get_dialog_create_work(Application $app){
    return $app['twig']->render('works\get_dialog_create_work.tpl');
  }

  public function get_dialog_create_workgroup(Application $app){
    return $app['twig']->render('works\get_dialog_create_workgroup.tpl');
  }

  public function get_dialog_exclude_event(Request $request, Application $app){
    $workgroup = $app['em']->getRepository('domain\workgroup')
                           ->findOneById($request->get('workgroup_id'));
    $event = $app['em']->getRepository('domain\event')
                       ->findOneById($request->get('event_id'));
    return $app['twig']->render('works\get_dialog_exclude_event.tpl',
                                [
                                 'workgroup' => $workgroup,
                                 'event' => $event
                                ]);
  }

  public function get_dialog_exclude_work(Request $request, Application $app){
    $workgroup = $app['em']->getRepository('\domain\workgroup')
                           ->findOneById($request->get('workgroup_id'));
    $work = $app['em']->getRepository('\domain\work')
                      ->findOneById($request->get('work_id'));
    return $app['twig']->render('works\get_dialog_exclude_work.tpl',
                                [
                                 'workgroup' => $workgroup,
                                 'work' => $work
                                ]);
  }

  public function get_dialog_rename_event(Request $request, Application $app){
    $event = $app['em']->getRepository('domain\event')
                      ->findOneById($request->get('id'));
    return $app['twig']->render('works\get_dialog_rename_event.tpl', ['event' => $event]);
  }

  public function get_dialog_rename_work(Request $request, Application $app){
    $work = $app['em']->getRepository('\domain\work')
                      ->findOneById($request->get('id'));
    return $app['twig']->render('works\get_dialog_rename_work.tpl', ['work' => $work]);
  }

  public function get_dialog_rename_workgroup(Request $request, Application $app){
    $workgroup = $app['em']->getRepository('\domain\workgroup')
                           ->findOneById($request->get('workgroup_id'));
    return $app['twig']->render('works\get_dialog_rename_workgroup.tpl', ['workgroup' => $workgroup]);
  }

  public function get_event_content(Request $request, Application $app){
    $event = $app['em']->getRepository('domain\event')
                      ->findOneById($request->get('id'));
    return $app['twig']->render('works\get_event_content.tpl', ['event' => $event]);
  }

  public function get_work_content(Request $request, Application $app){
    $work = $app['em']->getRepository('\domain\work')
                      ->findOneById($request->get('id'));
    return $app['twig']->render('works\get_work_content.tpl', ['work' => $work]);
  }

  public function get_workgroup_content(Request $request, Application $app){
    $workgroup = $app['em']->getRepository('\domain\workgroup')
                           ->findOneById($request->get('id'));
    return $app['twig']->render('works\get_workgroup_content.tpl', ['workgroup' => $workgroup]);
  }

  public function rename_event(Request $request, Application $app){
    $event = $app['em']->getRepository('domain\event')
                      ->findOneById($request->get('id'));
    $event->set_name($request->get('name'));
    $app['em']->flush();
    return $app['twig']->render('works\rename_event.tpl', ['event' => $event]);
  }

  public function rename_work(Request $request, Application $app){
    $work = $app['em']->getRepository('\domain\work')
                      ->findOneById($request->get('id'));
    $work->set_name($request->get('name'));
    $app['em']->flush();
    return $app['twig']->render('works\rename_work.tpl', ['work' => $work]);
  }

  public function rename_workgroup(Request $request, Application $app){
    $workgroup = $app['em']->getRepository('\domain\workgroup')
                           ->findOneById($request->get('workgroup_id'));
    $workgroup->set_name($request->get('name'));
    $app['em']->flush();
    return $app['twig']->render('works\rename_workgroup.tpl', ['workgroup' => $workgroup]);
  }
}