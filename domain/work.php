<?php namespace domain;

use DomainException;

/**
* @Entity
* @Table(name="works")
*/
class work{

  /**
   * @Id
   * @Column(name="id", type="integer")
   * @GeneratedValue
   */
  private $id;

  /**
  * @Column(name="name", type="string")
  */
  private $name;

  /**
  * @Column(name="status", type="string")
  */
  private $status;

  /*
  * @OneToMany(targetEntity="data_query2work", mappedBy="work")
  */
  private $query2works;

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public function set_id($id){
    if($id > 65535 OR $id < 1)
      throw new DomainException('Идентификатор работы задан не верно.');
    $this->id = $id;
  }

  public function set_status($status){
    $this->status = $status;
  }

  public function set_name($name){
    $this->name = $name;
  }
}