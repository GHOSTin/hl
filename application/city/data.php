<?php
/*
* Связь с таблицей `cities`.
* Города глобальны, но пока привязаны к компании.
*/
final class data_city extends data_object{
	
	public $id;
	public $company_id;
	public $status;
	public $name;
}