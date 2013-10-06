<?php
class model_processing_center{

  /*
  * Создает процессинговый центр.
  */
  public function create_processing_center($name){
    $mapper = new mapper_processing_center();
    $center = new data_processing_center();
    $center->set_name($name);
    $center->set_id($mapper->get_insert_id());
    return $mapper->insert($center);
  }

  /**
  * Возвращает список процессинговых центров
  * @return list object data_processing_center
  */
  public function get_processing_center($id){
    $center = (new mapper_processing_center)->find($id);
    if(!($center instanceof data_processing_center))
      throw new e_model('Процессингового центра не существует.');
    return $center;
  }

  /**
  * Возвращает список процессинговых центров
  * @return list object data_processing_center
  */
  public function get_processing_centers(){
    return (new mapper_processing_center)->get_processing_centers();
  }

  /*
  * Переименование процессингового центра.
  */
  public function rename_processing_center($center_id, $name){
    $center = $this->get_processing_center($center_id);
    $center->set_name($name);
    return (new mapper_processing_center())->update($center);
  }
}