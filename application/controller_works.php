<?php

use \boxxy\classes\di;

class controller_works{

  static $name = 'Работы';

  public static function private_show_default_page(model_request $request){
    $em = di::get('em');
    $groups = $em->getRepository('data_workgroup')->findAll();
    $works = $em->getRepository('data_work')->findAll();
    return ['groups' => $groups, 'works' => $works];
  }

  public static function private_get_workgroup_content(model_request $request){
    $workgroup = di::get('em')->getRepository('data_workgroup')
                              ->findOneById($request->GET('id'));
    return ['workgroup' => $workgroup];
  }

  public static function private_get_work_content(model_request $request){
    $work = di::get('em')->getRepository('data_work')
                              ->findOneById($request->GET('id'));
    return ['work' => $work];
  }

  public static function private_get_dialog_add_work(model_request $request){
    $works = di::get('em')->getRepository('data_work')->findAll();
    return ['works' => $works];
  }

  public static function private_get_dialog_create_workgroup(model_request $request){
  }

  public static function private_get_dialog_create_work(model_request $request){
  }

  public static function private_get_dialog_exclude_work(model_request $request){
    $em = di::get('em');
    $workgroup = $em->getRepository('data_workgroup')
                              ->findOneById($request->GET('workgroup_id'));
    $work = $em->getRepository('data_work')
               ->findOneById($request->GET('work_id'));
    return ['workgroup' => $workgroup, 'work' => $work];
  }

  public static function private_get_dialog_rename_work(model_request $request){
    $work = di::get('em')->getRepository('data_work')
                         ->findOneById($request->GET('id'));
    return ['work' => $work];
  }

  public static function private_get_dialog_rename_workgroup(model_request $request){
    $workgroup = di::get('em')->getRepository('data_workgroup')
                              ->findOneById($request->GET('workgroup_id'));
    return ['workgroup' => $workgroup];
  }

  public static function private_add_work(model_request $request){
    $em = di::get('em');
    $workgroup = $em->getRepository('data_workgroup')
                    ->findOneById($request->GET('workgroup_id'));
    $work = $em->getRepository('data_work')
               ->findOneById($request->GET('work_id'));
    $workgroup->add_work($work);
    $em->flush();
    return ['workgroup' => $workgroup];
  }

  public static function private_create_workgroup(model_request $request){
    $em = di::get('em');
    $workgroup = $em->getRepository('data_workgroup')
                    ->findOneByName($request->GET('name'));
    if(!is_null($workgroup))
      throw new RuntimeException('Такая группа существует.');
    $workgroup = new data_workgroup();
    $workgroup->set_name($request->GET('name'));
    $workgroup->set_status('active');
    $em->persist($workgroup);
    $em->flush();
    $workgroups = $em->getRepository('data_workgroup')->findAll();
    return ['workgroups' => $workgroups];
  }

  public static function private_create_work(model_request $request){
    $em = di::get('em');
    $work = $em->getRepository('data_work')
               ->findOneByName($request->GET('name'));
    if(!is_null($work))
      throw new RuntimeException('Такая группа существует.');
    $work = new data_work();
    $work->set_name($request->GET('name'));
    $work->set_status('active');
    $em->persist($work);
    $em->flush();
    $works = $em->getRepository('data_work')->findAll();
    return ['works' => $works];
  }

  public static function private_exclude_work(model_request $request){
    $em = di::get('em');
    $workgroup = $em->getRepository('data_workgroup')
                    ->findOneById($request->GET('workgroup_id'));
    $work = $em->getRepository('data_work')
               ->findOneById($request->GET('work_id'));
    $workgroup->exclude_work($work);
    $em->flush();
    return ['workgroup' => $workgroup];
  }

  public static function private_rename_workgroup(model_request $request){
    $em = di::get('em');
    $workgroup = $em->getRepository('data_workgroup')
                    ->findOneById($request->GET('workgroup_id'));
    $workgroup->set_name($request->GET('name'));
    $em->flush();
    return ['workgroup' => $workgroup];
  }

  public static function private_rename_work(model_request $request){
    $em = di::get('em');
    $work = $em->getRepository('data_work')
                    ->findOneById($request->GET('id'));
    $work->set_name($request->GET('name'));
    $em->flush();
    return ['work' => $work];
  }
}