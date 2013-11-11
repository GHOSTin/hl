<?php
/*
* Связь с таблицей `works`.
* В каждой компании свои работы.
*/
final class data_work extends data_object{

	private $company_id;
  private $id;
  private $name;

  public function set_id($id){
    $id = (int) $id;
    self::verify_id($id);
    $this->id = $id;
  }

  public function set_name($name){
    self::verify_name($name);
    $this->name = $name;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

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