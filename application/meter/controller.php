<?php

class controller_meter{

  public static function private_get_meter_content(model_request $request){
    return ['meter' => (new model_meter(di::get('company')))
      ->get_meter($request->GET('id'))];
  }

  public static function private_show_default_page(model_request $request){
    return ['meters' => (new model_meter(di::get('company')))
      ->get_meters()];
  }
}