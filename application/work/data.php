<?php
/*
* Связь с таблицей `works`.
* В каждой компании свои работы.
*/
final class data_work extends data_object{
	
	public $id;
	public $company_id;
	public $workgroup_id;
	public $status;
	public $name;
}