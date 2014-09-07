<?php
/**
* @Entity
* @Table(name="metrics")
*/
class data_metrics {

  /**
  * @Id
  * @Column(name="id", type="string")
  */
  private $id;

  /**
  * @Column(name="address", type="string")
  */
  private $address;

  /**
  * @Column(name="metrics", type="string")
  */
  private $metrics;

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

}