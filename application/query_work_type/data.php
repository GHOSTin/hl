<?php
final class data_query_work_type extends data_object{

    private $company_id;
	private $id;
	private $name;
    private $status;

    public function get_id(){
        return $this->id;
    }

    public function get_name(){
        return $this->name;
    }

    public function set_id($id){
        $this->id = (int) $id;
    }

    public function set_name($name){
        $this->name = (string) $name;
    }

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_query_work_type::$value($this);
    }
}