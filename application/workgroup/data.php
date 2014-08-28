<?php
/**
* @Entity
* @Table(name="workgroups")
*/
class data_workgroup extends data_object{

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
  * @Column(name="status", type="string")
  */
	private $status;

  /**
  * @OneToMany(targetEntity="data_work", mappedBy="workgroup")
  */
  private $works;

  public static $statuses = ['active', 'deactive'];

  public function add_work(data_work $work){
    if(array_key_exists($work->get_id(), $this->works))
      throw new DomainException('Такая работа уже добавлена в группу.');
    $this->works[$work->get_id()] = $work;
  }

  public function get_works(){
    return $this->works;
  }

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
    if($id > 65535 OR $id < 1)
      throw new DomainException('Идентификатор группы работ задан не верно.');
    $this->id = (int) $id;
  }

  public function set_name($name){
    $this->name = (string) $name;
  }

  public function set_status($status){
    if(!in_array($status, self::$statuses, true))
      throw new DomainException('Статус группы работ задан не верно.');
    $this->status = (string) $status;
  }
}