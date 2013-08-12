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

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_street::$value($this);
    }
}