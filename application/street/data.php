<?php
/*
* Связь с таблицей `streest`.
* Улицы глобальны, но пока привязаны к компании.
*/
final class data_street extends data_object{

    public $company_id;
    public $city_id;
    public $department_id;
    public $id;
    public $name;
	public $status;
}