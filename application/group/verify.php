<?php
class verify_group{

  /**
  * Верификация идентификатора группы.
  */
  public static function verify_id($id){
    if($id > 65535 OR $id < 1)
      throw new e_model('Идентификатор группы задан не верно.');
  }

  /**
  * Верификация названия группы.
  */
  public static function verify_name($name){
    // if(!preg_match('/^[АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ][а-я]{1,19}$/u', $name))
    //   throw new e_model('Название группы задано не верно.');
  }

  /**
  * Верификация статуса группы.
  */
  public static function verify_status($status){
    if(!in_array($status, ['false', 'true']))
      throw new e_model('Статус группы задан не верно.');
  }
}