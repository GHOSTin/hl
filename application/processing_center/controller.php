<?php
class controller_processing_center{

  static $name = 'Единый расчетный центр';

  public static function private_create_processing_center(
    model_request $request){
    (new model_processing_center)
      ->create_processing_center($request->GET('name'));
    return ['centers' => (new model_processing_center)
      ->get_processing_centers()];
  }

  public static function private_get_dialog_create_processing_center(
    model_request $request){
    return true;
  }

  public static function private_get_dialog_rename_processing_center(
    model_request $request){
    return ['center' => (new model_processing_center)
      ->get_processing_center($request->GET('id'))];
  }

  public static function private_get_processing_center_content(
    model_request $request){
    return ['center' => (new model_processing_center)
      ->get_processing_center($request->GET('id'))];
  }

  public static function private_rename_processing_center(
    model_request $request){
    return ['center' => (new model_processing_center)
      ->rename_processing_center($request->GET('id'), $request->GET('name'))];
  }
    
  public static function private_show_default_page(model_request $request){
    return ['centers' => (new model_processing_center)
      ->get_processing_centers()];
  }
}