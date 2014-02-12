<?php

class data_client_query{

  private $time;
  private $text;
  private $status;
  private $reason;
  private $query_id;
  private $number_id;
  private $company_id;

  private static $status_list = ['new', 'accepted', 'canceled'];
  private static $re = '|^[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]{6,255}$|u';

  public function get_company_id(){
    return $this->company_id;
  }

  public function get_number_id(){
    return $this->number_id;
  }

  public function get_query_id(){
    return $this->query_id;
  }

  public function get_reason(){
    return $this->reason;
  }

  public function get_status(){
    return $this->status;
  }

  public function get_time(){
    return $this->time;
  }

  public function get_text(){
    return $this->text;
  }

  public function set_company_id($id){
    if($id > 255 OR $id < 1)
      throw new DomainException();
    $this->company_id = (int) $id;
  }

  public function set_number_id($id){
    if($id > 16777215 OR $id < 1)
      throw new DomainException();
    $this->number_id = (int) $id;
  }

  public function set_query_id($id){
    if($id > 4294967295 OR $id < 1)
      throw new DomainException();
    $this->query_id = (int) $id;
  }

  public function set_reason($text){
    if(!preg_match(self::$re, $text))
      throw new DomainException();
    $this->reason = $text;
  }

  public function set_status($status){
    if(!in_array($status, self::$status_list))
      throw new DomainException();
    $this->status = $status;
  }

  public function set_time($time){
    if($time < 1 OR $time > 2145916800)
      throw new DomainException();
    $this->time = (int) $time;
  }

  public function set_text($text){
    if(!preg_match(self::$re, $text))
      throw new DomainException();
    $this->text = $text;
  }
}