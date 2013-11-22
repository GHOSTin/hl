<?php
class verify_city{
  
  /**
  * Верификация идентификатора.
  */
  public static function verify_id($id){
    if($id > 65535 OR $id < 1)
      throw new e_model('Идентификатор города задан не верно.');
  }

  /**
  * Верификация названия.
  */
  public static function verify_name($name){
    if(!preg_match('/^[а-яА-Я ]{0,20}$/u', $name))
      throw new e_model('Название города задано не верно.');
  }

  /**
  * Верификация статуса.
  */
  public static function verify_status($status){
    if(!in_array($status, data_city::$status_list, true))
      throw new e_model('Статус города задан не верно.');
  }
}