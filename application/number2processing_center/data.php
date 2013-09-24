<?php
class data_number2processing_center extends data_object{

  private $identifier;
  private $number;
  private $center;

  public function get_center(){
    return $this->center;
  }

  public function get_identifier(){
    return $this->identifier;
  }

  public function __construct(data_number $number, data_processing_center $center){
    $this->center = $center;
    $this->number = $number;
  }

  public function set_identifier($id){
    $this->identifier = $id;
  }

  public function verify(){
    if(func_num_args() < 0)
      throw new e_data('Параметры верификации не были переданы.');
    foreach(func_get_args() as $value)
      verify_number2processing_center::$value($this);
  }
}