<?php

use \boxxy\classes\di;

class controller_works{

	static $name = 'Работы';

	public static function private_show_default_page(model_request $request){
    $groups = di::get('em')->getRepository('data_workgroup')->findAll();
    $works = di::get('em')->getRepository('data_work')->findAll();
    return ['groups' => $groups, 'works' => $works];
	}

  public static function private_get_workgroup_content(model_request $request){
    $workgroup = di::get('em')->getRepository('data_workgroup')
                              ->findOneById($request->GET('id'));
    return ['workgroup' => $workgroup];
  }
}