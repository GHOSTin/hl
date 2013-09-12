<?php
/*
* Связь с таблицей `workgroups`.
* В каждой компании свои группы работы.
*/
final class data_workgroup extends data_object{

	private $company_id;
    private $id;
    private $name;
	private $status;

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_workgroup::$value($this);
    }
}