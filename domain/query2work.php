<?php namespace domain;

use BadMethodCallException;

/**
* @Entity
*/
class query2work{

  /**
  * @Id
  * @ManyToOne(targetEntity="domain\query", inversedBy="works")
  */
  private $query;

  /**
  * @Id
  * @ManyToOne(targetEntity="domain\work")
  */
  private $work;

  /**
  * @Column(name="opentime", type="integer")
  */
  private $time_open;

  /**
  * @Column(name="closetime", type="integer")
  */
  private $time_close;

  /**
  * @Column(nullable=true)
  */
  private $value;

  public function __call($method, $args){
    if(!in_array($method, get_class_methods($this->work)))
      throw new BadMethodCallException();
    return $this->work->$method($args);
  }

  public function __construct(query $query, work $work){
    $this->query = $query;
    $this->work = $work;
  }

  public function set_time_open($time){
    $this->time_open = (int) $time;
  }

  public function set_time_close($time){
    $this->time_close = (int) $time;
  }

  public function set_value($value){
    $this->value = (string) $value;
  }

  public function get_time_open(){
    return $this->time_open;
  }

  public function get_time_close(){
    return $this->time_close;
  }

  public function get_value(){
    return $this->value;
  }
}