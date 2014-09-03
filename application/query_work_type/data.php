<?php
/**
* @Entity
* @Table(name="query_worktypes")
*/
class data_query_work_type{

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
  * @OneToMany(targetEntity="data_query", mappedBy="work_type")
  */
  private $query;

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public function set_id($id){
    if($id > 255 OR $id < 1)
      throw new DomainException('Идентификатор типа заявки задан не верно.');
    $this->id = $id;
  }

  public function set_name($name){
    $this->name = $name;
  }
}