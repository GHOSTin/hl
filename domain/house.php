<?php namespace domain;

use DomainException;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity
* @Table(name="houses")
*/
class house{

  /**
  * @ManyToOne(targetEntity="\domain\department")
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
  * @ManyToOne(targetEntity="\domain\street")
  */
	private $street;

  /**
  * @OneToMany(targetEntity="\domain\flat", mappedBy="house")
  */
  private $flats;

  /**
  * @OneToMany(targetEntity="\domain\number", mappedBy="house")
  */
  private $numbers;

  /**
  * @OneToMany(targetEntity="\domain\query", mappedBy="house")
  */
  private $queries;

  private static $statuses = ['true', 'false'];

  public function __construct(){
    $this->numbers = new ArrayCollection();
    $this->flats = new ArrayCollection();
    $this->queries = new ArrayCollection();
  }

  public function __toString(){
    return (string) $this->number;
  }

  public function add_number(\domain\number $number){
    if($this->numbers->contains($number))
      throw new DomainException('Лицевой уже добавлен.');
    $this->numbers->add($number);
  }

  public function add_flat(\domain\flat $flat){
    if($this->flats->contains($flat))
      throw new DomainException('В доме уже существует такая квартира.');
    $this->flats->add($flat);
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

  public function set_department(\domain\department $department){
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

  public function set_street(\domain\street $street){
    $this->street = $street;
  }
}