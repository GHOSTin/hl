<?php

class controller_task {

  static $name = 'Задачи';
  static $rules = [];

  public static function private_show_default_page(model_request $request){
    return true;
  }

  public static function private_get_dialog_create_task(model_request $request){
    return ['users' => di::get('model_user')->get_users()];
  }

  public static function private_add_task(model_request $request){
    di::get('model_task')->add_task($request->GET('description'), (int) $request->GET('time_target'), $request->GET('performers'));
    return ['tasks' => di::get('mapper_task')->find_active_tasks()];
  }

  public static function private_close_task(model_request $request){
    di::get('model_task')->close_task($request->GET('id'),
        $request->GET('reason'), $request->GET('rating'), (int) $request->GET('time_close'));
    return ['tasks'=> di::get('mapper_task')->find_active_tasks()];
  }

  public static function private_show_active_tasks(model_request $request){
    return ['tasks' => di::get('mapper_task')->find_active_tasks()];
  }

  public static function private_show_finished_tasks(model_request $request){
    return ['tasks' => di::get('mapper_task')->find_finished_tasks()];
  }

  public static function private_get_task_content(model_request $request){
    return ['task'=> di::get('mapper_task')->find($request->GET('id'))];
  }

  public static function private_edit_task_content(model_request $request){
    return ['task'=> di::get('mapper_task')->find($request->GET('id')), 'users' => di::get('model_user')->get_users()];
  }

  public static function private_save_task_content(model_request $request){
    $task = di::get('model_task')->save_task($request->GET('id'), $request->GET('description'),
        (int) $request->GET('time_target'), $request->GET('performers'));
    return ['task'=> $task, 'tasks'=> di::get('mapper_task')->find_active_tasks()];
  }

  public static function private_get_dialog_close_task(model_request $request){
    return ['task'=> di::get('mapper_task')->find($request->GET('id'))];
  }

  public static function private_send_task_comment(model_request $request){
    di::get('model_task')->add_comment($request->GET('id'), $request->GET('message'));
    return ['comments'=> di::get('mapper_task2comment')->find_all($request->GET('id'))];
  }
}