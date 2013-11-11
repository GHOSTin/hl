<?php
class verify_work{

  /**
  * Верификация идентификатора работы.
  */
  public static function verify_id($id){
    if($id > 65535 OR $id < 1)
      throw new e_model('Идентификатор работы задан не верно.');
  }

  /**
  * Верификация названия работы.
  */
  public static function verify_name($name){
    // if(!preg_match('/^[А-Я][а-я ]{2,99}$/u', $name))
    //   throw new e_model('Название работы не удовлетворяет "а-яА-Я".');
  }
}