<?php

class data_import{

  private $file;
  private $xml;
  private $streets = [];

  public function __construct($file){
    $this->file = $file;
    if(!file_exists($file))
      throw new e_model('Файла для обработки не существует.');
    $this->xml = simplexml_load_file($file);
    if($this->xml === false)
      throw new e_model('Ошибка в xml файле.');
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
    $attr = $this->xml->attributes();
    $city = new data_city();
    $city->set_name($attr['city']);
    return $city;
  }

  public function get_house(){
    $attr = $this->xml->attributes();
    $house = new data_house();
    $house->set_number($attr['number']);
    return $house;
  }
}