<?php
class data_profile extends data_object{
  
  private $name;
  private $profile;
  private $rules;

  public function __construct($profile){
    $this->profile = (string) $profile;
  }

  public function __tostring(){
    return $this->profile;
  }

  public function get_rules(){
    return $this->rules;
  }

  public function set_name($name){
    $this->name = (string) $name;
  }

  public function set_rules(data_rule_collection $collection){
    $this->rules = $collection;
  }
}