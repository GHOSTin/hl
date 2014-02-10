<?php
class verify_house{

  /**
  * Верификация идентификатора.
  */
  public static function verify_id($id){
    if($id > 65535 OR $id < 1)
      throw new e_model('Идентификатор дома задан не верно.');
  }

  /**
  * Верификация номера.
  */
  public static function verify_number($number){
    if(!preg_match('|^[0-9]{1,3}[/]{0,1}[А-Яа-я0-9]{0,2}$|u', $number))
      throw new e_model('Номер дома задан не верно.');
  }

  /**
  * Верификация статуса.
  */
  public static function verify_status($status){
    if(!in_array($status, data_house::$statuses))
      throw new e_model('Статус дома задан не верно.');
  }
}