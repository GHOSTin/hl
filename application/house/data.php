<?php
/**
* @Entity
* @Table(name="houses")
*/
class data_house{

  /**
  * @ManyToOne(targetEntity="data_department")
  */
  private $department;

  /**
  * @Id
  * @Column(name="id", type="integer")
  */
	private $id;

  /**
  * @Column(name="housenumber", type="string")
  */
	private $number;

  /**
  * @Column(name="status", type="string")
  */
	private $status;

  /**
  * @ManyToOne(targetEntity="data_street")
  */
	private $street;
  private $centers = [];

  /**
  * @OneToMany(targetEntity="data_flat", mappedBy="house")
  */
  private $flats;

  /**
  * @OneToMany(targetEntity="data_number", mappedBy="house")
  */
  private $numbers;

  private static $statuses = ['true', 'false'];

  public function __construct($id = null){
    $this->id = (int) $id;
  }

  public function add_number(data_number $number){
    if(array_key_exists($number->get_id(), $this->numbers))
      throw new DomainException('Дом уже добавлен в улицу.');
    $this->numbers[$number->get_id()] = $number;
  }

  public function add_flat(data_flat $flat){
    if(array_key_exists($flat->get_id(), $this->flats))
      throw new DomainException('В доме уже существует такая квартира.');
    $this->flats[$flat->get_id()] = $flat;
  }

  public function get_city(){
    return $this->city;
  }

  public function get_department(){
    return $this->department;
  }

  public function get_flats(){
    return $this->flats;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_number(){
    return $this->number;
  }

  public function get_numbers(){
    return $this->numbers;
  }

  public function get_status(){
    return $this->status;
  }

  public function get_street(){
    return $this->street;
  }

  public function set_city(data_city $city){
    $this->city = $city;
  }

  public function set_department(data_department $department){
    $this->department = $department;
  }

  public function set_id($id){
    if($id > 65535 OR $id < 1)
      throw new DomainException('Идентификатор дома задан не верно.');
    $this->id = $id;
  }

  public function set_number($number){
    if(!preg_match('|^[0-9]{1,3}[/]{0,1}[А-Яа-я0-9]{0,2}$|u', $number))
      throw new DomainException('Номер дома задан не верно.');
    $this->number = $number;
  }

  public function set_status($status){
    if(!in_array($status, self::$statuses))
      throw new DomainException('Статус дома задан не верно.');
    $this->status = $status;
  }

  public function set_street(data_street $street){
    $this->street = $street;
  }

  private function send_error($message){
    throw new DomainException($message);
  }

  public function add_processing_center(data_house2processing_center $center){
    if(array_key_exists($center->get_id(), $this->centers))
      $this->send_error('К дому уже привязан процессинговый центр.');
    $this->centers[$center->get_id()] = $center;
  }

  public function remove_processing_center(data_house2processing_center $center){
    if(!array_key_exists($center->get_id(), $this->centers))
      $this->send_error('Процессинговый центр не привязан к дому.');
    unset($this->centers[$center->get_id()]);
  }

  public function get_processing_centers(){
    return $this->centers;
  }
}