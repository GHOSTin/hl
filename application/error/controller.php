<?php
class controller_error{
  public static function error404(model_request $request){
    return true;
  }

  public static function private_show_dialog(model_request $request){
    return true;
  }

  public static function private_send_error(model_request $request){
    (new model_error)->send_error($request->GET('text'));
  }

  public static function private_show_default_page(model_request $request){
    return ['errors' => (new model_error)->get_errors()];
  }
}