<?php

class model_street2house{

  private $street;

  public function __construct(data_street $street){
    $this->street = $street;
    data_street::verify_id($this->street->get_id());
  }

  public function init_houses(){
    (new mapper_street2house($this->street))->init_houses();
  }

  public function create_house($house_number){
    $mapper = new mapper_street2house($this->street);
    if(!is_null($mapper->find_by_number($house_number)))
      throw new e_model('Такой дом уже существует.');
    $h_array = ['id' => $mapper->get_insert_id(), 'number' => $house_number,
    'street' => $this->street];
    $house = di::get('factory_house')->build($h_array);
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