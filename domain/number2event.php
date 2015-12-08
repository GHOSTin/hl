<?php namespace domain;

use DateTime;

/**
* @Entity(repositoryClass="domain\repositories\number2event")
*/
class number2event{

  /**
  * @Id
  * @Column
  */
  private $id;

  /**
  * @Column(type="integer")
  */
  private $time;

  /**
  * @ManyToOne(targetEntity="domain\number")
  */
  private $number;

  /**
  * @ManyToOne(targetEntity="domain\event")
  */
  private $event;

  /**
  * @Column
  */
  private $description;

  public function __construct(number $number, event $event, $date, $description = ''){
    $time = DateTime::createFromFormat('H:i d.m.Y', '12:00 '.$date);
    $this->time = $time->getTimeStamp();
    $this->description = $description;
    $this->id = $number->get_id().'-'.$event->get_id().'-'.$this->time;
    $this->event = $event;
    $this->number = $number;
  }

  public function get_time(){
    return $this->time;
  }

  public function get_description(){
    return $this->description;
  }

  public function get_name(){
    return $this->event->get_name();
  }

  public function get_id(){
    return $this->id;
  }
}
