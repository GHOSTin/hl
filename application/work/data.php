<?php
/**
* @Entity
* @Table(name="works")
*/
class data_work extends data_object{

  /**
   * @Id
   * @Column(name="id", type="integer")
   */
  private $id;

  /**
  * @Column(name="name", type="string")
  */
  private $name;

  /**
  * @ManyToOne(targetEntity="data_workgroup")
  */
  private $workgroup;

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

  public function set_name($name){
    $this->name = $name;
  }
}