<?php
/*
* Связь с таблицей `users`.
* Пользователи глобальны и к ним могут быть определены компании,
* в которых они учавствуют.
*/
class data_user extends data_object{

	public $cellphone;
	public $company_id;
	public $firstname;
	public $id;
	public $lastname;
	public $login;
	public $middlename;
	public $password;
	public $session;
	public $status;
	public $telephone;

  public function get_status(){
    return $this->status;
  }

	public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_user::$value($this);
    }
}