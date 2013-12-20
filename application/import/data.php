<?php

class data_import{

  private $file;
  private $xml;

  public function __construct($file){
    $this->file = $file;
    if(!file_exists($file))
      throw new e_model('Файла для обработки не существует.');
    $this->xml = simplexml_load_file($file);
    if($this->xml === false)
      throw new e_model('Ошибка в xml файле.');
    $attr = $this->xml->attributes();
    $this->city = (new model_city)->get_city_by_name($attr['city']);
  }

  public function get_xml(){
    return $this->xml;
  }

  public function get_file(){
    return $this->file;
  }

  public function get_street(){
    $attr = $this->xml->attributes();
    $street = new data_street();
    $street->set_name($attr['street']);
    return $street;
  }

  public function get_city(){
    return $this->city;
  }

  public function get_flats(){
    $flats = [];
    if(!empty($this->xml->flat))
      foreach($this->xml->flat as $flat_node)
        $flats[] = (string) $flat_node->attributes()->number;
    return $flats;
  }

  public function get_house(){
    $attr = $this->xml->attributes();
    $house = new data_house();
    $house->set_number($attr['number']);
    return $house;
  }
}