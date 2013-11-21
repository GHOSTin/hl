<?php
class verify_department{

  /**
  * Верификация идентификатора участка.
  */
  public static function verify_id($id){
    if($id > 255 OR $id < 1)
      throw new e_model('Идентификатор участка задан не верно.');
  }

  /**
  * Верификация названия участка.
  */
  public static function verify_name($name){
    if(!preg_match('/^[0-9а-яА-Я №]{3,19}$/u', $name))
      throw new e_model('Название участка задано не верно.');
  }

  /**
  * Верификация статуса участка.
  */
  public static function verify_status($status){
    if(!in_array($status, data_department::$statuses))
      throw new e_model('Статус участка задан не верно.');
  }
}