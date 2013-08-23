<?php
/*
* Связь с таблицей `flats`.
* Квартиры глобальны, но пока привязаны к компании.
*/
final class data_flat extends data_object{

	public $company_id;
	public $house_id;
    public $id;
    public $number;
	public $status;

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_flat::$value($this);
    }
}