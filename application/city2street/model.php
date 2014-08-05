<?php
class model_city2street{

  public function __construct(data_city $city){
    $this->city = $city;
    data_city::verify_id($this->city->get_id());
  }

  public function create_street($name){
    $mapper = new mapper_city2street($this->city);
    if(!is_null($mapper->get_street_by_name($name)))
      throw new RuntimeException('Такая улица уже существует.');
    return $mapper->insert(di::get('factory_street')
      ->build(['id' => $mapper->get_insert_id(), 'name' => $name]));
  }

  public function get_street($id){
    $street = (new mapper_city2street($this->city))->get_street($id);
    if(!($street instanceof data_street))
      throw new RuntimeException('Нет улицы');
    return $street;
  }

  public function get_street_by_name($name){
    $street = (new mapper_city2street($this->city))->get_street_by_name($name);
    if(!($street instanceof data_street))
      throw new RuntimeException('Нет улицы');
    return $street;
  }
}