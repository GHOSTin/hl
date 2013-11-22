<?php
class verify_workgroup{
  
  /**
  * Верификация идентификатора группы работ.
  */
  public static function verify_id($id){
    if($id > 65535 OR $id < 1)
      throw new e_model('Идентификатор группы работ задан не верно.');
  }

  /**
  * Верификация названия группы работ.
  */
  public static function verify_name($name){
    // if(!preg_match('/^[А-Я][а-я ]{2,99}$/u', $name))
    //   throw new e_model('Название группы работ не удовлетворяет "а-яА-Я".');
  }

  /**
  * Верификация статуса группы работ.
  */
  public static function verify_status($status){
    if(!in_array($status, data_workgroup::$statuses, true))
      throw new e_model('Статус группы работ задан не верно.');
  }
}