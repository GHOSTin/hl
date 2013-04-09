<?php
/*
* Связь с таблицей `workgroups`.
* В каждой компании свои группы работы.
*/
final class data_workgroup extends data_object{

	public $id;
	public $company_id;
	public $status;
	public $name;
}