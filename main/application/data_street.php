<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity
* @Table(name="streets")
*/
class data_street{

  /**
  * @ManyToOne(targetEntity="data_city")
  */
  private $city;

  /**
  * @Id
  * @Column(name="id", type="integer")
  */
  private $id;

  /**
  * @Column(name="name", type="string")
  */
  private $name;

  /**
  * @Column(name="status", type="string")
  */
  private $status;

  /**
  * @OneToMany(targetEntity="data_house", mappedBy="street")
  */
  private $houses;

  public static $statuses = ['true', 'false'];

  public function __construct(){
    $this->houses = new ArrayCollection();
  }

  public function add_house(data_house $house){
    if($this->houses->contains($house))
      throw new DomainException('House exists.');
    $this->houses->add($house);
  }

  public function get_houses(){
    return $this->houses;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public function get_status(){
    return $this->status;
  }

  public function set_id($id){
    if($id > 65535 OR $id < 1)
      throw new DomainException('Идентификатор улицы задан не верно.');
    $this->id = $id;
  }

  public function set_name($name){
    if(!preg_match('/^[0-9а-яА-Я-_ ]{3,20}$/u', $name))
      throw new DomainException('Название улицы задано не верно.');
    $this->name = $name;
  }

  public function set_status($status){
    if(!in_array($status, self::$statuses))
      throw new DomainException('Статус улицы задан не верно.');
    $this->status = $status;
  }
}