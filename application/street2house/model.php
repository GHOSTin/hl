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

  public function create_house($house_number){
    $mapper = new mapper_street2house($this->street);
    if(!is_null($mapper->find_by_number($house_number)))
      throw new e_model('Такой дом уже существует.');
    $house = new data_house();
    $house->set_id($mapper->get_insert_id());
    $house->set_number($house_number);
    $house->set_status('true');
    return $mapper->insert($house);
  }

  public function get_house($id){
    $house = (new mapper_street2house($this->street))->find($id);
    if(!($house instanceof data_house))
      throw new e_model('Дом не существует.');
    return $house;
  }

  public function get_house_by_number($number){
    $house = (new mapper_street2house($this->street))->find_by_number($number);
    if(!($house instanceof data_house))
      throw new e_model('Дом не существует.');
    return $house;
  }
}