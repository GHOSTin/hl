<?php
/*
* Связь с таблицей `users`.
* Пользователи глобальны и к ним могут быть определены компании,
* в которых они учавствуют.
*/
final class data_user extends data_object{

	public $id;
	public $status;
	public $login;
	public $firstname;
	public $middlename;
	public $lastname;
	public $session;
	public $company_id;
	public $telephone;
	public $cellphone;
	public $password;
}