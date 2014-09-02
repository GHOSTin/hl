<?php
/**
* @Entity
* @Table(name="query2user")
*/
class data_query2user{

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
  * @ManyToOne(targetEntity="data_user")
  */
  private $user;

  public function get_class(){
    return $this->class;
  }

  public function __call($method, $args){
    if(!in_array($method, get_class_methods($this->user)))
      throw new BadMethodCallException();
    return $this->user->$method($args);
  }
}