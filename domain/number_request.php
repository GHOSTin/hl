<?php namespace domain;

use DomainException;

/**
* @Entity
* @Table(name="number_requests")
*/
class number_request{

  /**
  * @Column
  */
  private $message;

  /**
  * @Id
  * @ManyToOne(targetEntity="domain\number")
  */
  private $number;

  /**
  * @OneToOne(targetEntity="domain\query", inversedBy="request")
  */
  private $query;

  /**
  * @Id
  * @Column(type="integer")
  */
  private $time;

  public function __construct(number $number, $message){
    if(!preg_match(query::RE_DESCRIPTION, $message))
      throw new DomainException('Описание запроса заданы не верно.');
    $this->number = $number;
    $this->message = $message;
    $this->time = time();
  }

  public function get_message(){
    return $this->message;
  }

  public function get_number(){
    return $this->number;
  }

  public function get_query(){
    return $this->query;
  }

  public function get_time(){
    return $this->time;
  }

  public function set_query(query $query){
    $this->query = $query;
  }
}