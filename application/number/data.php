<?php
/*
* Связь с таблицей `numbers`.
* Лицевые счета ассоциированы с компанией и с городом.
* В одном городе у одной компании не может быть два одинаковых лицевых счета.
*/
final class data_number extends data_object{
	
	public $id;
	public $company_id;
	public $city_id;
	public $house_id;
	public $flat_id;
	public $number;
	public $type;
	public $status;
	public $fio;
	public $telephone;
	public $cellphone;
	public $password;
	public $contact_fio;
	public $contact_telephone;
	public $contact_cellphone;
	// other
	public $flat_number;
	public $house_number;
	public $street_name;
	public $department_id;
}