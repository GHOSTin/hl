<?php
final class data_query_work_type extends data_object{

    private $company_id;
	private $id;
	private $name;
    private $status;

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_query_work_type::$value($this);
    }
}