<?php
/**
* @Entity
* @Table(name="sessions_logs")
*/
class data_session{

  /**
  * @Id
  * @Column(name="ip", type="string")
  */
  public $ip;

  /**
  * @Id
  * @Column(name="time", type="integer")
  */
  public $time;

  /**
  * @ManyToOne(targetEntity="data_user")
  */
  public $user;

  public function get_ip(){
    return $this->ip;
  }

  public function get_time(){
    return $this->time;
  }

  public function get_user(){
    return $this->user;
  }

  public function set_ip($ip){
    $this->ip = $ip;
  }

  public function set_time($time){
    $this->time = $time;
  }

  public function set_user(data_user $user){
    $this->user = $user;
  }
}