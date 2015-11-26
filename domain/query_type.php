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
  * @Column
  */
  private $name;

  /**
  * @Column
  */
  private $color = 'cccccc';


  public function get_color(){
    return $this->color;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public static function new_instance($name){
    $query_type = new self();
    $query_type->set_name($name);
    return $query_type;
  }

  public function set_color($color){
    if(!preg_match('/^[0-9a-f]{6}$/u', $color))
      throw new DomainException('Wrong query_type color '.$color);
    $this->color = $color;
  }

  public function set_name($name){
    $name = trim($name);
    if(!preg_match('/^[а-яА-Я -]{1,255}$/u', $name))
      throw new DomainException('Wrong query_type name '.$name);
    $this->name = $name;
  }
}