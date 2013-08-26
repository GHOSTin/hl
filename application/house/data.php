<?php
/*
* Связь с таблицей `houses`.
* Дома глобальны, но пока привязаны к компании.
*/
final class data_house extends data_object{
	
	public $city_id;
    public $city_name;
	public $company_id;
	public $department_id;
	public $id;
	public $number;
	public $status;
	public $street_id;
	public $street_name;

  public function set_id($id){
    $this->id = $id;
  }

  public function get_id(){
    return $this->id;
  }

	public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_house::$value($this);
    }
}