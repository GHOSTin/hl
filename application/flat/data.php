<?php
/**
* @Entity
* @Table(name="flats")
*/
class data_flat{

  /**
  * @ManyToOne(targetEntity="data_house")
  */
  private $house;

  /**
  * @Id
  * @Column(name="id", type="integer")
  */
  private $id;

  /**
  * @OneToMany(targetEntity="data_number", mappedBy="flat")
  */
  private $num;

  /**
  * @Column(name="flatnumber", type="string")
  */
  private $number;

  /**
  * @Column(name="status", type="string")
  */
  private $status;

  private static $statuses = ['true', 'false'];

  public function get_house(){
    return $this->house;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_number(){
    return $this->number;
  }

  public function get_status(){
    return $this->status;
  }

  public function set_house(data_house $house){
    $this->house = $house;
  }

  public function set_id($id){
    if($id > 16777215 OR $id < 1)
      throw new DomainException('Идентификатор квартиры задан не верно.');
    $this->id = $id;
  }

  public function set_number($number){
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