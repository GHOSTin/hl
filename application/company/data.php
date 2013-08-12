<?php
/*
* Связь с таблицей `companies`.
* Компании глобальны для системы.
*/
final class data_company extends data_object{

	public $id;
    public $name;
	public $status;

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_company::$value($this);
    }
}