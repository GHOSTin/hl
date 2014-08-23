<?php

class controller_metrics {

  static $name = 'Показания';
  static $rules = [];

  public static function private_show_default_page(model_request $request){
    return ['metrics' => di::get('mapper_metrics')->find_all()];
  }

  public static function private_remove_metrics(model_request $request){
    $ids = $request->POST('metric');
    return ['ids' => di::get('model_metrics')->remove($ids)];
  }
} 