<?php
class model_city2street{

  public function __construct(data_city $city){
    $this->city = $city;
    $this->city->verify('id');
  }

  public function create_street($street_name){
    $mapper = new mapper_city2street($this->city);
    if(!is_null($mapper->get_street_by_name($street_name)))
      throw new e_model('Такая улица уже существует.');
    $street = new data_street();
    $street->set_id($mapper->get_insert_id());
    $street->set_name($street_name);
    $street->set_status('true');
    return $mapper->insert($street);
  }

  public function get_street($id){
    $street = (new mapper_city2street($this->city))->get_street($id);
    if(!($street instanceof data_street))
      throw new e_model('Нет улицы');
    return $street;
  }

  public function get_street_by_name($name){
    $street = (new mapper_city2street($this->city))->get_street_by_name($name);
    if(!($street instanceof data_street))
      throw new e_model('Нет улицы');
    return $street;
  }
}