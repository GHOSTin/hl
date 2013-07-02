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

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_group::$value($this);
    }
}