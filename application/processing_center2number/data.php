<?php
class data_processing_center2number extends data_object{

    private $company_id;
    private $identifier;
    private $number_id;
    private $processing_center_id;
    private $processing_center_name;

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_processing_center2number::$value($this);
    }
}