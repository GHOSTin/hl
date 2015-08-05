<?php namespace domain;

use DomainException;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

/**
* @Entity
* @Table(name="flats")
*/
class flat implements JsonSerializable{

  /**
  * @ManyToOne(targetEntity="domain\house")
  */
  private $house;

  /**
  * @Id
  * @Column(type="integer")
  * @GeneratedValue
  */
  private $id;

  /**
  * @OneToMany(targetEntity="domain\number", mappedBy="flat")
  */
  private $num;

  /**
  * @Column(name="flatnumber")
  */
  private $number;

  /**
  * @OneToMany(targetEntity="domain\number", mappedBy="flat")
  */
  private $numbers;

  /**
  * @Column
  */
  private $status;

  public function __construct(){
    $this->numbers = new ArrayCollection();
    $this->status = 'true';
  }

  public function __toString(){
    return (string) $this->number;
  }

  public function get_house(){
    return $this->house;
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

  public function JsonSerialize(){
    return [
             'id' => $this->id,
             'number' => $this->number
           ];
  }

  public static function new_instance(house $house, $number){
    $flat = new self();
    $flat->house = $house;
    $flat->set_number($number);
    return $flat;
  }

  public function set_number($number){
    $number = trim($number);
    if(!preg_match('|^[0-9]{1,3}.{0,1}[0-9]{0,1}$|', $number))
      throw new DomainException('Номер квартиры задан не верно.');
    $this->number = $number;
  }
}