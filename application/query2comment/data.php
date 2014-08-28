<?php
/**
* @Entity
* @Table(name="query2comment")
*/
class data_query2comment{

  /**
  * @Id
  * @Column(name="time", type="integer")
  */
  private $time;

  /**
  * @Id
  * @Column(name="query_id", type="integer")
  */
  private $query_id;

  /**
  * @Id
  * @Column(name="user_id", type="integer")
  */
  private $user_id;

  /**
  * @Column(name="message", type="string")
  */
  private $message;

  /**
  * @ManyToOne(targetEntity="data_query")
  */
  private $query;

  /**
  * @ManyToOne(targetEntity="data_user")
  */
  private $user;

  public function get_user(){
    return $this->user;
  }

  public function get_message(){
    return $this->message;
  }

  public function get_time(){
    return $this->time;
  }

  public function set_time($time){
    $this->time = (int) $time;
  }

  public function set_message($message){
    $this->message = (string) $message;
  }

  public function set_user(data_user $user){
    $this->user = $user;
  }
}