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

  public static function private_get_dialog_add_work(model_request $request){
    $works = di::get('em')->getRepository('data_work')->findAll();
    return ['works' => $works];
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
}