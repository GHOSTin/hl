<?php namespace domain;

use Doctrine\Common\Collections\ArrayCollection;
use DomainException;
use JsonSerializable;

/**
* @Entity
* @Table(name="streets")
*/
class street implements JsonSerializable{

  /**
  * @Id
  * @Column(type="integer")
  * @GeneratedValue
  */
  private $id;

  /**
  * @Column
  */
  private $name;

  /**
  * @Column
  */
  private $status;

  /**
  * @OneToMany(targetEntity="domain\house", mappedBy="street")
  */
  private $houses;

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

  public function JsonSerialize(){
    return [
             'id' => $this->id,
             'name' => $this->name
           ];
  }

  public static function new_instance($name){
    $street = new street();
    $street->set_name($name);
    return $street;
  }

  public function set_name($name){
    $name = trim($name);
    if(!preg_match('/^[0-9а-яА-Я-_ ]{3,20}$/u', $name))
      throw new DomainException('Название улицы задано не верно.');
    $this->name = $name;
  }
}