<?php
/**
* Связь с таблицей `processing_centers`
*/
class data_processing_center extends data_object{

    public $id;
    public $name;

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_processing_center::$value($this);
    }
}