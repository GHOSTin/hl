<?php
/**
* @Entity
* @Table(name="query2work")
*/
class data_query2work{

  /**
  * @Id
  * @Column(name="query_id", type="integer")
  */
  private $query_id;

  /**
  * @Id
  * @Column(name="work_id", type="integer")
  */
  private $work_id;

  /**
  * @ManyToOne(targetEntity="data_work")
  */
  private $work;

  /**
  * @Column(name="opentime", type="integer")
  */
  private $time_open;

  /**
  * @Column(name="closetime", type="integer")
  */
  private $time_close;

  /**
  * @Column(name="value", type="string")
  */
  private $value;

  public function __call($method, $args){
    if(!in_array($method, get_class_methods($this->work)))
      throw new BadMethodCallException();
    return $this->work->$method($args);
  }

  public function __construct(data_query $query, data_work $work){
    $this->query = $query;
    $this->query_id = $query->get_id();
    $this->work = $work;
    $this->work_id = $work->get_id();
  }

  public function set_time_open($time){
    $this->time_open = (int) $time;
  }

  public function set_time_close($time){
    $this->time_close = (int) $time;
  }

  public function set_value($value){
    $this->value = (string) $value;
  }

  public function get_time_open(){
    return $this->time_open;
  }

  public function get_time_close(){
    return $this->time_close;
  }

  public function get_value(){
    return $this->value;
  }
}