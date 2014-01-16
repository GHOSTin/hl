<?php

final class data_meter2data extends data_object{

  private $time;
  private $values = [];
  private $comment;
  private $way;
  private $timestamp;
  private $company_id;
  private $number_id;
  private $meter_id;
  private $serial;
  private static $ways = ['answerphone', 'telephone', 'fax', 'personally'];

  public function get_comment(){
    return $this->comment;
  }

  public function get_company_id(){
    return $this->company_id;
  }

  public function get_meter_id(){
    return $this->meter_id;
  }

  public function get_number_id(){
    return $this->number_id;
  }

  public function get_serial(){
    return $this->serial;
  }

  public function get_time(){
    return $this->time;
  }

  public function get_timestamp(){
    return $this->timestamp;
  }

  public function get_values(){
    return $this->values;
  }

  public function get_way(){
    return $this->way;
  }

  public function set_comment($comment){
    self::verify_comment($comment);
    $this->comment = $comment;
  }

  public function set_company_id($id){
    data_company::verify_id($id);
    $this->company_id = $id;
  }

  public function set_number_id($id){
    data_number::verify_id($id);
    $this->number_id = $id;
  }

  public function set_meter_id($id){
    data_meter::verify_id($id);
    $this->meter_id = $id;
  }

  public function set_serial($serial){
    data_meter::verify_serial($serial);
    $this->serial = $serial;
  }

  public function set_time($time){
    verify_environment::verify_time($time);
    $this->time = $time;
  }

  public function set_timestamp($time){
    verify_environment::verify_time($time);
    $this->timestamp = $time;
  }

  public function set_value($key, $value){
    $this->values[(int) $key] = (int) $value;
  }

  public function set_way($way){
    self::verify_way($way);
    $this->way = $way;
  }

  public static function verify_comment($comment){
    if(!preg_match('/^[А-Яа-я0-9\., ]{0,255}$/u', $comment))
      throw new e_model('Комментарий задан не верно.');
  }

  public static function verify_way($way){
    if(!in_array($way, self::$ways, true))
      throw new e_model('Спсоб передачи показания задан не верно.');
  }
}