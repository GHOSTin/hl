<?php
/**
* @Entity
* @Table(name="companies")
*/
class data_company{

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

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public function set_id($id){
    if($id > 255 OR $id < 1)
      throw new DomainException('Wrong company id.');
    $this->id = $id;
  }

  public function set_name($name){
    if(!preg_match('/^[А-Яа-я0-9 ]{2,20}$/u', $name))
      throw new DomainException('Wrong company name.');
    $this->name = $name;
  }
}