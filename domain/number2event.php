<?php namespace domain;

/**
* @Entity(repositoryClass="domain\repositories\number2event")
* @Table(name="number2event")
*/
class number2event{

  /**
  * @Id
  * @Column(type="integer")
  */
  private $time;

  /**
  * @Id
  * @ManyToOne(targetEntity="domain\number")
  */
  private $number;

  /**
  * @Id
  * @ManyToOne(targetEntity="domain\event")
  */
  private $event;

  public function get_event(){
    return $this->event;
  }

  public function get_number(){
    return $this->number;
  }

  public function get_time(){
    return $this->time;
  }

  public function set_event(event $event){
    $this->event = $event;
  }

  public function set_number(number $number){
    $this->number = $number;
  }

  public function set_time($time){
    $this->time = (int) $time;
  }

  public function get_name(){
    return $this->event->get_name();
  }

  public function get_id(){
    return $this->event->get_id();
  }
}