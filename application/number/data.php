<?php
/*
* Связь с таблицей `numbers`.
* Лицевые счета ассоциированы с компанией и с городом.
* В одном городе у одной компании не может быть два одинаковых лицевых счета.
*/
final class data_number extends data_object{
	
	private $cellphone;
	private $city_id;
	private $contact_cellphone;
	private $contact_fio;
	private $contact_telephone;
	private $company_id;
	private $department_id;
	private $fio;
	private $flat_number;
	private $flat_id;
	private $house_id;
	private $house_number;
	private $id;
	private $number;
	private $password;
	private $status;
	private $street_name;
	private $telephone;
	private $type;

	public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_number::$value($this);
    }

    public function get_id(){
        return $this->id;
    }

    public function get_fio(){
        return $this->fio;
    }

    public function get_flat_number(){
        return $this->flat_number;
    }

    public function get_number(){
        return $this->number;
    }

    public function set_cellphone($cellphone){
    	$this->cellphone = $cellphone;
    }

    public function set_fio($fio){
    	$this->fio = $fio;
    }

    public function set_flat_number($number){
        $this->flat_number = (string) $number;
    }

    public function set_id($id){
    	$this->id = $id;
    }

    public function set_number($number){
    	$this->number = $number;
    }

    public function set_company_id($id){
        $this->company_id = (int) $id;
    }

    public function set_city_id($id){
        $this->city_id = (int) $id;
    }

    public function set_house_id($id){
        $this->house_id = (int) $id;
    }

    public function set_flat_id($id){
        $this->flat_id = (int) $id;
    }

    public function set_type($type){
        $this->type = (string) $type;
    }

    public function set_status($status){
        $this->status = (string) $status;
    }

    public function set_telephone($telephone){
    	$this->telephone = $telephone;
    }

    public function get_house_id(){
      return $this->house_id;
    }
}