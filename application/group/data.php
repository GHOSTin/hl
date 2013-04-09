<?php
/*
* Связь с таблицей `groups`.
* Группы уникальны в каждой компании.
*/
final class data_group extends data_object{

	public $id;
	public $status;
	public $company_id;
	public $name;
}