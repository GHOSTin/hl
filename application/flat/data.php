<?php
/*
* Связь с таблицей `flats`.
* Квартиры глобальны, но пока привязаны к компании.
*/
final class data_flat extends data_object{
	
	public $id;
	public $company_id;
	public $house_id;
	public $status;
	public $number;
}