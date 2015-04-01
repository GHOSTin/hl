<?php namespace domain;

use DomainException;

/**
* @Entity
* @Table(name="query_types")
*/
class query_type{

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

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public static function new_instance($name){
    $query_type = new query_type();
    $query_type->set_name($name);
    return $query_type;
  }

  public function set_name($name){
    $name = trim($name);
    if(!preg_match('/^[а-яА-Я -]{1,255}$/u', $name))
      throw new DomainException('Wrong query_type name '.$name);
    $this->name = $name;
  }
}