<?php
final class data_query_work_type extends data_object{

    public $company_id;
	public $id;
	public $name;
    public $status;

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_query_work_type::$value($this);
    }
}