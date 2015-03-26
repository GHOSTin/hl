<?php namespace domain;

use Doctrine\Common\Collections\ArrayCollection;
use DomainException;

/**
* @Entity
* @Table(name="streets")
*/
class street{

  /**
  * @Id
  * @Column(name="id", type="integer")
  * @GeneratedValue
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
  * @OneToMany(targetEntity="\domain\house", mappedBy="street")
  */
  private $houses;

  public static $statuses = ['true', 'false'];

  public function __construct(){
    $this->houses = new ArrayCollection();
    $this->status = 'true';
  }

  public function __toString(){
    return (string) $this->name;
  }

  public function add_house(house $house){
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

  public static function new_instance($name){
    $street = new street();
    $street->set_name($name);
    return $street;
  }

  public function set_id($id){
    if($id > 65535 OR $id < 1)
      throw new DomainException('Идентификатор улицы задан не верно.');
    $this->id = $id;
  }

  public function set_name($name){
    $name = trim($name);
    if(!preg_match('/^[0-9а-яА-Я-_ ]{3,20}$/u', $name))
      throw new DomainException('Название улицы задано не верно.');
    $this->name = $name;
  }
}