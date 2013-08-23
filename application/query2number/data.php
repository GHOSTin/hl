<?php
class data_query2number extends data_object{

	private $company;
	private $query;
    private $type;
    private $number;

	public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_query2number::$value($this);
    }
   
    public function get_company(){
        return $this->company;
    }

    public function get_number(){
        return $this->number;
    }

    public function get_type(){
        return $this->type;
    }

    public function get_query(){
        return $this->query;
    }

    public function set_company(data_company $company){
        $this->company = $company;
    }

    public function set_number(data_number $number){
        $this->number = $number;
    }

    public function set_type($type){
        $this->type = $type;
    }

    public function set_query(data_query $query){
        $this->query = $query;
    }
}