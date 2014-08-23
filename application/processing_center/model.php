<?php
class model_processing_center{

  public function create_processing_center($name){
    $mapper = di::get('mapper_processing_center');
    $center = new data_processing_center();
    $center->set_name($name);
    $center->set_id($mapper->get_insert_id());
    return $mapper->insert($center);
  }

  public function get_processing_center($id){
    $center = di::get('mapper_processing_center')->find($id);
    if(!($center instanceof data_processing_center))
      throw new RuntimeException('Процессингового центра не существует.');
    return $center;
  }

  public function get_processing_centers(){
    return di::get('mapper_processing_center')->get_processing_centers();
  }

  public function rename_processing_center($center_id, $name){
    $center = $this->get_processing_center($center_id);
    $center->set_name($name);
    return di::get('mapper_processing_center')->update($center);
  }
}