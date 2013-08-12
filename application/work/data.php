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

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_work::$value($this);
    }
}