<?php
class data_number2processing_center{

  private $identifier;
  private $processing_center;

  public function __call($method, $args){
    return $this->processing_center->$method($args);
  }

  public function __construct(data_processing_center $center){
    $this->processing_center = $center;
  }

  public function get_identifier(){
    return $this->identifier;
  }

  public function set_identifier($id){
    if(!preg_match('/^[а-яА-Яa-zA-Z0-9]{0,20}$/u', $ident))
      throw new DomainException('Идентификатор лицевого счета задан не верно.');
    $this->identifier = $id;
  }
}