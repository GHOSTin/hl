<?php
class model_house2processing_center{

  private $company;
  private $house;

  public function __construct(data_company $company, data_house $house){
    $this->company = $company;
    $this->house = $house;
    data_company::verify_id($this->company->get_id());
    data_house::verify_id($this->house->get_id());
  }

  public function add_processing_center($center_id, $identifier){
    $this->house = (new model_house)->get_house($this->house->get_id());
    $center = new data_house2processing_center(
      (new model_processing_center)->get_processing_center($center_id));
    $center->set_identifier($identifier);
    $mapper = new mapper_house2processing_center($this->company, $this->house);
    $mapper->init_processing_centers();
    $this->house->add_processing_center($center);
    $mapper->update_processing_centers();
    return $this->house;
  }

  public function remove_processing_center($center_id){
    $this->house = (new model_house)->get_house($this->house->get_id());
    $center = new data_house2processing_center(
      (new model_processing_center)->get_processing_center($center_id));
    $mapper = new mapper_house2processing_center($this->company, $this->house);
    $mapper->init_processing_centers();
    $this->house->remove_processing_center($center);
    $mapper->update_processing_centers();
    return $this->house;
  }

  public function init_processing_centers(){
    (new mapper_house2processing_center($this->company, $this->house))
      ->init_processing_centers();
  }
}