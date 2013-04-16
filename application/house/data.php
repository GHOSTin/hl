<?php
/*
* Связь с таблицей `houses`.
* Дома глобальны, но пока привязаны к компании.
*/
final class data_house extends data_object{
	
	public $id;
	public $company_id;
	public $city_id;
	public $street_id;
	public $department_id;
	public $status;
	public $number;
	public $street_name;
    public $city_name;
}