<?php

class controller_task {

  public static function private_show_default_page(model_request $request){
    return true;
  }

  public static function private_get_dialog_create_task(model_request $request){
    return ['users' => di::get('model_user')->get_users()];
  }

  public static function private_add_task(model_request $request){
    di::get('model_task')->add_task($request->GET('description'), (int) $request->GET('time_close'), $request->GET('performers'));
    return true;
  }
}