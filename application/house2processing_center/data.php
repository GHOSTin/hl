<?php
class data_house2processing_center{

  private $processing_center;
  private $identifier;

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

  public function set_identifier($identifier){
    $this->identifier = (string) $identifier;
  }
}