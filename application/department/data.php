<?php
/*
* Связь с таблицей `departments`.
* В каждой компании свои участки.
*/
final class data_department extends data_object{

	public $company_id;
    public $id;
	public $name;
    public $status;

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_department::$value($this);
    }
}