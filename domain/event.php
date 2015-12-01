<?php namespace domain;

use DomainException;

/**
* @Entity
* @Table(name="events")
*/
class event{

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

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public static function new_instance($name){
    $event = new self();
    $event->set_name($name);
    return $event;
  }

  public function set_name($name){
    if(!preg_match('/^[а-яА-Я -]{1,255}$/u', $name))
      throw new DomainException('Wrong event name'.$name);
    $this->name = $name;
  }
}