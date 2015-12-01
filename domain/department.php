<?php namespace domain;

use DomainException;

/**
* @Entity
* @Table(name="departments")
*/
class department{

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

  public function get_status(){
    return $this->status;
  }

  public static function create($name){
    $department = new self();
    if(!preg_match('/^[0-9а-яА-Я №]{3,19}$/u', $name))
      throw new DomainException('Название участка задано не верно '.$name);
    $department->name = $name;
    return $department;
  }
}