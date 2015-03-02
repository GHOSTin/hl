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
  * @Column(name="user_id", type="string")
  */
  private $user_id;

  /**
  * @Id
  * @Column(name="query_id", type="string")
  */
  private $query_id;

  /**
  * @ManyToOne(targetEntity="\domain\user")
  */
  private $user;

  public function __call($method, $args){
    if(!in_array($method, get_class_methods($this->user)))
      throw new BadMethodCallException();
    return $this->user->$method($args);
  }

  public function __construct(query $query, user $user){
    $this->user = $user;
    $this->user_id = $user->get_id();
    $this->query = $query;
    $this->query_id = $query->get_id();
  }

  public function get_class(){
    return $this->class;
  }

  public function set_class($class){
    $this->class = $class;
  }
}