<?php
/*
* Связь с таблицей `workgroups`.
* В каждой компании свои группы работы.
*/
final class data_workgroup extends data_object{

	public $company_id;
    public $id;
    public $name;
	public $status;
}