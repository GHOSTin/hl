<?php
class verify_meter{

  public static function verify_capacity($capacity){
    if($capacity < 1 OR $capacity > 9)
      throw new e_model('Разрядность задана не верно.');
  }

  public static function verify_id($id){
    if($id > 16777215 OR $id < 1)
      throw new e_model('Идентификатор счетчика задан не верно.');
  }

  public static function verify_name($name){
    if(!preg_match('/^[а-яА-Яa-zA-Z0-9 -]{1,20}$/u', $name))
      throw new e_model('Название счетчика задано не верно.');
  }

  public static function verify_period($period){
    if($period < 0 OR $period > 241)
        throw new e_model('Период задан не верно.');
  }

  public static function verify_rates($rates){
    if($rates < 1 OR $rates > 3)
      throw new e_model('Тарифность задана не верно.');
  }

  public static function verify_service($service){
    if(!in_array($service, data_meter::$service_list))
      throw new e_model('Услуга задана не верно.');
  }
}