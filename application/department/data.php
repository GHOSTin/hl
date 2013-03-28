<?php
/*
* Связь с таблицей `departments`.
* В каждой компании свои участки.
*/
final class data_department extends data_object{

	public $id;
	public $company_id;
	public $status;
	public $name;
}