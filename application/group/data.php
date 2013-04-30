<?php
/*
* Связь с таблицей `groups`.
* Группы уникальны в каждой компании.
*/
final class data_group extends data_object{

	public $company_id;
    public $id;
    public $name;
    public $status;
}