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
	public $date_release;
	public $date_install;
	public $date_checking;
	public $period;
	public $place;

	public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_number2meter::$value($this);
    }
}