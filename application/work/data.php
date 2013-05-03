<?php
/*
* Связь с таблицей `works`.
* В каждой компании свои работы.
*/
final class data_work extends data_object{

	public $company_id;
    public $id;
    public $name;
    public $status;
	public $workgroup_id;
}