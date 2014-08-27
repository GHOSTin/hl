<?php
/**
* @Entity
* @Table(name="departments")
*/
class data_department extends data_object{

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
  * @OneToMany(targetEntity="data_house", mappedBy="department")
  */
  private $houses;

  /**
  * @OneToMany(targetEntity="data_query", mappedBy="department")
  */
  private $queries;

  /**
  * @Column(name="status", type="string")
  */
  private $status;

  private static $statuses = ['active', 'deactive'];

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public function get_status(){
    return $this->status;
  }

  public function set_id($id){
    if($id > 255 OR $id < 1)
      throw new DomainException('Идентификатор участка задан не верно.');
    $this->id = $id;
  }

  public function set_name($name){
    if(!preg_match('/^[0-9а-яА-Я №]{3,19}$/u', $name))
      throw new DomainException('Название участка задано не верно.');
    $this->name = $name;
  }

  public function set_status($status){
    if(!in_array($status, self::$statuses))
      throw new DomainException('Статус участка задан не верно.');
    $this->status = (string) $status;
  }
}