<?php
class data_profile extends data_object{
  
  private $profile;
  private $rules;
  private $restrictions;

  public function __construct($profile){
    $this->profile = (string) $profile;
  }

  public function __tostring(){
    return $this->profile;
  }

  public function get_rules(){
    return $this->rules;
  }

  public function set_rules(array $rules){
    $this->rules = $rules;
  }

  public function set_restrictions(array $restrictions){
    $this->restrictions = $restrictions;
  }

  public function get_restrictions(){
    return $this->restrictions;
  }
}