<?php
class data_rule_collection extends data_object{

  private $rules;

  public function add_rule($key, $status){
    $this->rules[$key] = $status;
  }

  public function get_rules(){
    return $this->rules;
  }
}