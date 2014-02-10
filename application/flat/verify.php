<?php
class verify_flat{

  /**
  * Верификация идентификатора квартиры.
  */
  public static function verify_id($id){
    if($id > 16777215 OR $id < 1)
      throw new e_model('Идентификатор квартиры задан не верно.');
  }

  /**
  * Верификация номера квартиры.
  */
  public static function verify_number($number){
    if(!preg_match('|^[0-9]{1,3}.{0,1}[0-9]{0,1}$|', $number))
      throw new e_model('Номер квартиры задан не верно.');
  }

  /**
  * Верификация статуса квартиры.
  */
  public static function verify_status($status){
    if(!in_array($status, data_flat::$statuses, true))
      throw new e_model('Статус квартиры задан не верно.');
  }
}