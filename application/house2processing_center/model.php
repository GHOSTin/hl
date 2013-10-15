<?php
class model_house2processing_center{

  private $company;
  private $house;

  public function __construct(data_company $company, data_house $house){
    $this->company = $company;
    $this->house = $house;
    $this->company->verify('id');
    $this->house->verify('id');
  }

  public function add_processing_center($center_id, $identifier){
    $this->house = (new model_house)->get_house($this->house->get_id());
    $center = (new model_processing_center)->get_processing_center($center_id);
    $mapper = new mapper_house2processing_center($this->company, $this->house);
    $mapper->init_processing_centers();
    $this->house->add_processing_center($center, $identifier);
    $mapper->update_processing_centers();
    return $this->house;
  }

  public function init_processing_centers(){
    $mapper = new mapper_house2processing_center($this->company, $this->house);
    $mapper->init_processing_centers();
  }
}