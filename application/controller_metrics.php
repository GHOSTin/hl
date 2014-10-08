<?php

use Doctrine\Common\Collections\Criteria;
use \boxxy\classes\di;

class controller_metrics {

  static $name = 'Показания';
  static $rules = [];

  public static function private_show_default_page(model_request $request){
    return ['metrics' => di::get('em')
      ->getRepository('data_metrics')->findByStatus('actual')];
  }

  public static function private_remove_metrics(model_request $request){
    $em = di::get('em');
    $ids = $request->POST('metric');
    if(!empty($ids))
      foreach($ids as $id){
        $metric = $em->find('data_metrics', $id);
        if(!is_null($metric))
          $metric->set_status('archive');
      }
    $em->flush();
    return ['ids' => $ids];
  }

  public static function private_archive(model_request $request){
    return null;
  }

  public static function private_set_date(model_request $request){
    $archive = di::get('em')
        ->getRepository('data_metrics')->findByStatusBetween('archive', $request->GET('time'));
    return ['metrics' => $archive];
  }
}