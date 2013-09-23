<?php
class model_street2house{

  private $street;

  public function __construct(data_street $street){
    $this->street = $street;
    $this->street->verify('id');
  }

  public function init_houses(){
    $mapper = new mapper_street2house($this->street);
    $mapper->init_houses();
  }
}