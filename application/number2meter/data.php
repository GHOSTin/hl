<?php
/*
* Связь с таблицей `numbers`.
* Лицевые счета ассоциированы с компанией и с городом.
* В одном городе у одной компании не может быть два одинаковых лицевых счета.
*/
final class data_number2meter extends data_object{

	public $company_id;
	public $number_id;
	public $meter_id;
	public $service;
	public $name;
	public $rates;
	public $serial;
	public $data_release;
	public $data_install;
	public $data_checking;
	public $period;
	public $place;
}