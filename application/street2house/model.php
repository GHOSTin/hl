<?php

class model_street2house{

  private $street;

  public function __construct(data_street $street){
    $this->street = $street;
    data_street::verify_id($this->street->get_id());
  }

  public function init_houses(){
    di::get('mapper_street2house')->init_houses($this->street);
  }

  public function create_house($house_number){
    $mapper = di::get('mapper_street2house');
    if(!is_null($mapper->find_by_number($this->street, $house_number)))
      throw new e_model('Такой дом уже существует.');
    $h_array = ['id' => $mapper->get_insert_id(), 'number' => $house_number,
    'street' => $this->street];
    $house = di::get('factory_house')->build($h_array);
    return $mapper->insert($this->street, $house);
  }

  public function get_house($id){
    $house = di::get('mapper_street2house')->find($this->street, $id);
    if(!($house instanceof data_house))
      throw new e_model('Дом не существует.');
    return $house;
  }

  public function get_house_by_number($number){
    $house = di::get('mapper_street2house')
      ->find_by_number($this->street, $number);
    if(!($house instanceof data_house))
      throw new e_model('Дом не существует.');
    return $house;
  }
}