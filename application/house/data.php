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
  private $centers = [];

  public function set_id($id){
    $this->id = $id;
  }

  public function get_id(){
    return $this->id;
  }

  private function send_error($message){
    throw new e_model($message);
  }

  public function add_processing_center(data_processing_center $center, $identifier){
    if(array_key_exists($center->id, $this->centers))
      $this->send_error('К дому уже привязан процессинговый центр.');
    $this->centers[$center->id] = [$center, $identifier];
  }

  public function remove_processing_center(data_processing_center $center){
    if(!array_key_exists($center->id, $this->centers))
      $this->send_error('Процессинговый центр не привязан к дому.');
    unset($this->centers[$center->id]);
  }

  public function get_processing_centers(){
    return $this->centers;
  }

	public function verify(){
    if(func_num_args() < 0)
      $this->send_error('Параметры верификации не были переданы.');
    foreach(func_get_args() as $value)
        verify_house::$value($this);
  }
}