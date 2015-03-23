<?php namespace domain;

/**
* @Entity
* @Table(name="sessions_logs")
*/
class session{

  /**
  * @Id
  * @Column(type="string")
  */
  public $ip;

  /**
  * @Id
  * @Column(type="integer")
  */
  public $time;

  /**
  * @ManyToOne(targetEntity="domain\user")
  */
  public $user;

  public function __construct(){
    $this->time = time();
  }

  public function get_ip(){
    return $this->ip;
  }

  public function get_time(){
    return $this->time;
  }

  public function get_user(){
    return $this->user;
  }

  public static function new_instance(user $user, $ip){
    $session = new session();
    $session->user = $user;
    $session->ip = $ip;
    return $session;
  }
}