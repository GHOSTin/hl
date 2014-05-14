<?php

class controller_export {
  public static function private_show_default_page(model_request $request){
    return true;
  }

  public static function private_get_dialog_export_numbers(
      model_request $request){
    return true;
  }

  public static function private_export_numbers(model_request $request){
    di::get('model_export')->export_numbers(true);
    return true;
  }

} 