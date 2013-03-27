<?php
/*
* Связь с таблицей `streest`.
* Улицы глобальны, но пока привязаны к компании.
*/
final class data_street extends data_object{
	
	public $id;
	public $company_id;
	public $city_id;
	public $status;
	public $name;
}