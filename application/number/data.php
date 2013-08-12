<?php
/*
* Связь с таблицей `numbers`.
* Лицевые счета ассоциированы с компанией и с городом.
* В одном городе у одной компании не может быть два одинаковых лицевых счета.
*/
final class data_number extends data_object{
	
	public $cellphone;
	public $city_id;
	public $contact_cellphone;
	public $contact_fio;
	public $contact_telephone;
	public $company_id;
	public $department_id;
	public $fio;
	public $flat_number;
	public $flat_id;
	public $house_id;
	public $house_number;
	public $id;
	public $number;
	public $password;
	public $status;
	public $street_name;
	public $telephone;
	public $type;

	public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_number::$value($this);
    }

    public function set_cellphone($cellphone){
    	$this->cellphone = $cellphone;
    }

    public function set_id($id){
    	$this->id = $id;
    }

    public function set_number($number){
    	$this->number = $number;
    }
}