<?php
/**
* @Entity
* @Table(name="errors")
*/
class data_error{

  /**
  * @Column(name="text", type="string")
  */
  private $text;

  /**
  * @ManyToOne(targetEntity="data_user")
  */
  private $user;

  /**
  * @Id
  * @Column(name="time", type="integer")
  */
  private $time;

  public function get_user(){
    return $this->user;
  }

  public function get_text(){
    return $this->text;
  }

  public function get_time(){
    return $this->time;
  }

  public function set_user(data_user $user){
    $this->user = $user;
  }

  public function set_text($text){
    $this->text = $text;
  }

  public function set_time($time){
    $this->time = $time;
  }
}