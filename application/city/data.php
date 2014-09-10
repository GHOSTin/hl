<?php
/**
* @Entity
* @Table(name="cities")
*/
class data_city{

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
  * @ManyToOne(targetEntity="data_number")
  */
  private $numbers;

  /**
  * @Column(name="status", type="string")
  */
	private $status;

  /**
   * @OneToMany(targetEntity="data_street", mappedBy="city")
   */
  private $streets;

  private static $status_list = ['false', 'true'];

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
    if($id > 255 OR $id < 1)
      throw new DomainException('Идентификатор города задан не верно.');
    $this->id = $id;
  }

  public function set_name($name){
    if(!preg_match('/^[а-яА-Я -]{2,20}$/u', $name))
      throw new DomainException('Название города задано не верно.');
    $this->name = $name;
  }

  public function set_status($status){
    if(!in_array($status, self::$status_list, true))
      throw new DomainException('Статус города задан не верно.');
    $this->status = $status;
  }
}