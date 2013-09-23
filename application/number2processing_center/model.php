<?php
class model_number2processing_center{

  private $company;
  private $number;

  public function __construct(data_company $company, data_number $number){
    $this->company = $company;
    $this->number = $number;
    $this->company->verify('id');
    $this->number->verify('id');
  }

  public function init_processing_centers(){
    (new mapper_number2processing_center($this->company, $this->number))->init_processing_centers();
  }
}