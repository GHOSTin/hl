<?php
class model_house2number{
  
  private $company;
  private $house;


  public function __construct(data_company $company, data_house $house){
    $this->company = $company;
    $this->house = $house;
    data_company::verify_id($this->company->get_id());
    $this->house->verify('id');
  }

  public function init_numbers(){
    $mapper = new mapper_house2number($this->company, $this->house);
    $mapper->init_numbers();
  }
}