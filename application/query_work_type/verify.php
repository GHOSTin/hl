<?php
class verify_query_work_type{

  /**
  * Верификация идентификатора типа работа заявки.
  */
  public static function verify_id($id){
    if($id > 255 OR $id < 1)
      throw new e_model('Идентификатор типа заявки задан не верно.');
  }

  /**
  * Верификация названия типа работ заявки.
  */
  public static function verify_name($name){
    if(!preg_match('/^[А-Я][а-я]{2,19}$/u', $name))
      throw new e_model('Название типа работ задано не верно.');
  }

  /**
  * Верификация статуса типа работ заявки.
  */
  public static function verify_status($status){
    if(!in_array($query_work_type->status, ['active', 'deactive']))
      throw new e_model('Статус типа работ задан не верно.');
  }
}