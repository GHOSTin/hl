<?php namespace domain;

use BadMethodCallException;
/**
* @Entity
* @Table(name="query2user")
*/
class query2user{

  /**
  * @Id
  * @Column(name="class", type="string")
  */
  private $class;

  /**
  * @Id
  * @ManyToOne(targetEntity="domain\query")
  */
  private $query;

  /**
  * @Id
  * @ManyToOne(targetEntity="domain\user")
  */
  private $user;

  public function __call($method, $args){
    if(!in_array($method, get_class_methods($this->user)))
      throw new BadMethodCallException();
    return $this->user->$method($args);
  }

  public function __construct(query $query, user $user){
    $this->user = $user;
    $this->query = $query;
  }

  public function get_class(){
    return $this->class;
  }

  public function set_class($class){
    $this->class = $class;
  }
}