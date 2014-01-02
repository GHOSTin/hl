<?php
class data_user extends data_object{

	private $cellphone;
	private $company_id;
	private $firstname;
	private $id;
	private $lastname;
	private $login;
	private $middlename;
	private $password;
	private $session;
	private $status;
	private $telephone;
  private $hash;

  public static $statuses = ['true', 'false'];

  public static function __callStatic($method, $args){
    if(!in_array($method, get_class_methods('verify_user'), true))
      throw new e_model('Нет доступного метода.');
    return verify_user::$method($args[0]);
  }

  public function get_cellphone(){
    return $this->cellphone;
  }

  public function get_company_id(){
    return $this->company_id;
  }

  public function get_firstname(){
    return $this->firstname;
  }
  public function get_hash(){
    return $this->hash;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_lastname(){
    return $this->lastname;
  }

  public function get_login(){
    return $this->login;
  }

  public function get_middlename(){
    return $this->middlename;
  }

  public function get_status(){
    return $this->status;
  }

  public function get_telephone(){
    return $this->telephone;
  }

  public function set_cellphone($cellphone){
    $this->cellphone = (string) $cellphone;
    self::verify_cellphone($this->cellphone);
  }

  public function set_id($id){
    $this->id = (int) $id;
    self::verify_id($this->id);
  }

  public function set_company_id($id){
    $this->company_id = (int) $id;
  }

  public function set_firstname($firstname){
    $this->firstname = (string) $firstname;
    self::verify_firstname($this->firstname);
  }

  public function set_hash($hash){
    $this->hash = (string) $hash;
  }

  public function set_lastname($lastname){
    $this->lastname = (string) $lastname;
    self::verify_lastname($this->lastname);
  }

  public function set_login($login){
    $this->login = (string) $login;
    self::verify_login($this->login);
  }

  public function set_middlename($middlename){
    $this->middlename = (string) $middlename;
    self::verify_middlename($this->middlename);
  }

  public function set_status($status){
    $this->status = (string) $status;
    self::verify_status($this->status);
  }

  public function set_telephone($telephone){
    $this->telephone = (string) $telephone;
    self::verify_telephone($this->telephone);
  }
}