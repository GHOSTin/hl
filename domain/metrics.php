<?php namespace domain;

/**
* @Entity(repositoryClass="\domain\repositories\metrics")
* @Table(name="metrics")
*/
class metrics {

  /**
  * @Id
  * @Column
  */
  private $id;

  /**
  * @Column
  */
  private $address;

  /**
  * @Column
  */
  private $metrics;
  /**
  * @Column(name="date", type="bigint")
  * @var
  */
  private $time;
  /**
  * @Column
  * @var
  */
  private $status;

  /**
  * @return mixed
  */
  public function get_status()
  {
    return $this->status;
  }

  /**
   * @param mixed $status
   */
  public function set_status($status)
  {
    $this->status = $status;
  }
  /**
   * @return mixed
   */
  public function get_time()
  {
    return $this->time;
  }

  /**
   * @param mixed $time
   */
  public function set_time($time)
  {
    $this->time = $time;
  }
  /**
   * @param mixed $address
   */
  public function set_address($address)
  {
    $this->address = $address;
  }

  /**
   * @return mixed
   */
  public function get_address()
  {
    return $this->address;
  }

  /**
   * @param mixed $id
   */
  public function set_id($id)
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function get_id()
  {
    return $this->id;
  }

  /**
   * @param mixed $metrics
   */
  public function set_metrics($metrics)
  {
    $this->metrics = $metrics;
  }

  /**
   * @return mixed
   */
  public function get_metrics()
  {
    return $this->metrics;
  }

  public static function new_instance($id, $time, $address, $message){
    $metrics = new self();
    $metrics->set_id($id);
    $metrics->set_time($time);
    $metrics->set_address($address);
    $metrics->set_metrics($message);
    $metrics->set_status('actual');
    return $metrics;
  }
}