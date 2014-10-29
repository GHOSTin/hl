<?php
/**
* @Entity
* @Table(name="workgroups")
*/
class data_workgroup{

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
   * @ManyToMany(targetEntity="data_work")
   * @JoinTable(name="workgroup2work",
   * joinColumns={@JoinColumn(name="workgroup_id", referencedColumnName="id")},
   * inverseJoinColumns={@JoinColumn(name="work_id", referencedColumnName="id")})
   */
  private $works;

  public static $statuses = ['active', 'deactive'];

  public function add_work(data_work $work){
    if($this->works->contains($work))
      throw new DomainException('Такая работа уже добавлена в группу.');
    $this->works->add($work);
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