<?php namespace domain;

use DomainException;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

/**
* @Entity
* @Table(name="houses")
*/
class house implements JsonSerializable{

  /**
  * @ManyToOne(targetEntity="domain\department")
  */
  private $department;

  /**
  * @Id
  * @Column(type="integer")
  * @GeneratedValue
  */
	private $id;

  /**
  * @Column(name="housenumber")
  */
	private $number;

  /**
  * @Column
  */
	private $status;

  /**
  * @ManyToOne(targetEntity="domain\street")
  */
	private $street;

  /**
  * @OneToMany(targetEntity="domain\flat", mappedBy="house")
  */
  private $flats;

  /**
  * @OneToMany(targetEntity="domain\number", mappedBy="house")
  */
  private $numbers;

  /**
  * @OneToMany(targetEntity="domain\query", mappedBy="house")
  */
  private $queries;

  public function __construct(){
    $this->numbers = new ArrayCollection();
    $this->flats = new ArrayCollection();
    $this->queries = new ArrayCollection();
    $this->status = 'true';
  }

  public function __toString(){
    return (string) $this->number;
  }

  public function add_number(number $number){
    if($this->numbers->contains($number))
      throw new DomainException('Лицевой уже добавлен.');
    $this->numbers->add($number);
  }

  public function add_flat(flat $flat){
    if($this->flats->contains($flat))
      throw new DomainException('В доме уже существует такая квартира.');
    $this->flats->add($flat);
  }

  public function get_debtors($limit){
    $numbers = [];
    foreach($this->get_numbers() as $number){
      if($number->get_debt() > $limit)
        $numbers[$number->get_debt()][] = $number;
    }
    return $numbers;
  }

  public function get_full_name(){
    return $this->get_street()->get_name().', дом. №'.$this->number;
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

  public function get_queries(){
    return $this->queries;
  }

  public function JsonSerialize(){
    return [
             'id' => $this->id,
             'number' => $this->number
           ];
  }

  public static function new_instance(department $department, street $street,  $house_number){
    $house = new self();
    $house->set_number($house_number);
    $house->street = $street;
    $house->department = $department;
    return $house;
  }

  public function set_department(department $department){
    $this->department = $department;
  }

  public function set_number($number){
    $number = trim($number);
    if(!preg_match('|^[0-9]{1,3}[/]{0,1}[А-Яа-я0-9]{0,2}$|u', $number))
      throw new DomainException('Номер дома "'.$number.'" задан не верно.');
    $this->number = $number;
  }
}