<?php

class controller_metrics {

  static $name = 'Показания';
  static $rules = [];

  public static function private_show_default_page(model_request $request){
    return ['metrics' => di::get('em')->getRepository('data_metrics')->findAll()];
  }

  public static function private_remove_metrics(model_request $request){
    $em = di::get('em');
    $ids = $request->POST('metric');
    if(!empty($ids))
      foreach($ids as $id){
        $metric = $em->find('data_metrics', $id);
        if(!is_null($metric))
          $em->remove($metic);
      }
    $em->flush();
    return ['ids' => $ids];
  }
}