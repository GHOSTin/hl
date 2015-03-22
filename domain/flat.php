<?php namespace domain;

use DomainException;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity
* @Table(name="flats")
*/
class flat{

  /**
  * @ManyToOne(targetEntity="\domain\house")
  */
  private $house;

  /**
  * @Id
  * @Column(name="id", type="integer")
  * @GeneratedValue
  */
  private $id;

  /**
  * @OneToMany(targetEntity="\domain\number", mappedBy="flat")
  */
  private $num;

  /**
  * @Column(name="flatnumber", type="string")
  */
  private $number;

  /**
  * @OneToMany(targetEntity="\domain\number", mappedBy="flat")
  */
  private $numbers;
  /**
  * @Column(name="status", type="string")
  */
  private $status;

  private static $statuses = ['true', 'false'];

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

  public static function new_instance(house $house, $number){
    $flat = new flat();
    $flat->set_house($house);
    $flat->set_number($number);
    return $flat;
  }

  public function set_house(house $house){
    $this->house = $house;
  }

  public function set_id($id){
    if($id > 16777215 OR $id < 1)
      throw new DomainException('Идентификатор квартиры задан не верно.');
    $this->id = $id;
  }

  public function set_number($number){
    $number = trim($number);
    if(!preg_match('|^[0-9]{1,3}.{0,1}[0-9]{0,1}$|', $number))
      throw new DomainException('Номер квартиры задан не верно.');
    $this->number = $number;
  }

  public function set_status($status){
    if(!in_array($status, self::$statuses, true))
      throw new DomainException('Статус квартиры задан не верно.');
    $this->status = $status;
  }
}