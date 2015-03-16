<?php namespace domain;

use DomainException;

/**
* @Entity
* @Table(name="works")
*/
class work{

  /**
  * @Id
  * @Column(type="integer")
  * @GeneratedValue
  */
  private $id;

  /**
  * @Column(type="string")
  */
  private $name;

  /**
  * @Column(type="string")
  */
  private $status;

  public function __construct(){
    $this->status = 'active';
  }

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public static function new_instance($name){
    $work = new work();
    $work->set_name($name);
    return $work;
  }

  public function set_name($name){
    if(!preg_match('/^[а-яА-Я -]{1,255}$/u', $name))
      throw new DomainException('Wrong work name'.$name);
    $this->name = $name;
  }
}