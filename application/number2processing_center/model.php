<?php
class model_number2processing_center{

  private $company;
  private $number;

  public function __construct(data_company $company, data_number $number){
    $this->company = $company;
    $this->number = $number;
  }

  public function add_processing_center($center_id, $identifier){
    $this->init_processing_centers();
    $center = (new model_processing_center)->get_processing_center($center_id);
    $n2c = new data_number2processing_center($center);
    $n2c->set_identifier($identifier);
    $this->number->add_processing_center($n2c);
    (new mapper_number2processing_center($this->company, $this->number))
    ->update();
  }

  public function delete_processing_center($center_id){
    $this->init_processing_centers();
    $center = (new model_processing_center)->get_processing_center($center_id);
    $n2c = new data_number2processing_center($center);
    $this->number->delete_processing_center($n2c);
    (new mapper_number2processing_center($this->company, $this->number))
    ->update();
  }

  public function init_processing_centers(){
    (new mapper_number2processing_center($this->company, $this->number))->init_processing_centers();
  }
}