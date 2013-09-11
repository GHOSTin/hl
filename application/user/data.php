<?php
/*
* Связь с таблицей `users`.
* Пользователи глобальны и к ним могут быть определены компании,
* в которых они учавствуют.
*/
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


  public function get_cellphone(){
    return $this->cellphone;
  }

  public function get_company_id(){
    return $this->company_id;
  }

  public function get_firstname(){
    return $this->firstname;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_lastname(){
    return $this->lastname;
  }

  public function get_login(){
    return $this->lastname;
  }

  public function get_middlename(){
    return $this->middlename;
  }

  public function get_password(){
    return $this->password;
  }

  public function get_status(){
    return $this->status;
  }

  public function get_telephone(){
    return $this->telephone;
  }

	public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_user::$value($this);
    }
}