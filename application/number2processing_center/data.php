<?php
class data_number2processing_center{

  private $identifier;
  private $processing_center;

  public function __call($method, $args){
    return $this->processing_center->$method($args);
  }

  public function __construct(data_processing_center $center){
    $this->processing_center = $center;
    $this->processing_center->verify('id');
  }

  public function get_identifier(){
    return $this->identifier;
  }

  public function set_identifier($id){
    $this->identifier = (string) $id;
  }

  public function verify(){
    if(func_num_args() < 0)
      throw new e_data('Параметры верификации не были переданы.');
    foreach(func_get_args() as $value)
      verify_number2processing_center::$value($this);
  }
}