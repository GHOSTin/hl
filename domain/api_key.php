<?php namespace domain;

use DomainException;

/**
* @Entity
*/
class api_key{

  /**
  * @Column
  */
  private $hash;

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

  public function get_hash(){
    return $this->hash;
  }

  public function get_name(){
    return $this->name;
  }

  public static function new_instance($name){
    $name = trim($name);
    if(!preg_match('/^[0-9а-яА-Я -]{1,255}$/u', $name))
      throw new DomainException('Wrong name '.$name);
    $key = new self();
    $key->name = $name;
    $key->hash = sha1($name.time());
    return $key;
  }
}