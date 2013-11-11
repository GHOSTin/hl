<?php
class verify_street{

  /**
  * Верификация идентификатора.
  */
  public static function verify_id($id){
    if($id > 65535 OR $id < 1)
      throw new e_model('Идентификатор улицы задан не верно.');
  }

  /**
  * Верификация статуса.
  */
  public static function verify_status($status){
    if(!in_array($status, data_street::$statuses))
      throw new e_model('Статус улицы задан не верно.');
  }

  /**
  * Верификация названия.
  */
  public static function verify_name($name){
    if(!preg_match('/^[0-9а-яА-Я-_ ]{3,20}$/u', $name))
      throw new e_model('Название улицы задано не верно.');
  }
}