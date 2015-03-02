<?php namespace domain;

use DomainException;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity
* @Table(name="workgroups")
*/
class workgroup{

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

  /**
   * @ManyToMany(targetEntity="\domain\work")
   * @JoinTable(name="workgroup2work",
   * joinColumns={@JoinColumn(name="workgroup_id", referencedColumnName="id")},
   * inverseJoinColumns={@JoinColumn(name="work_id", referencedColumnName="id")})
   */
  private $works;

  /**
   * @ManyToMany(targetEntity="domain\event")
   * @JoinTable(name="workgroup2event",
   * joinColumns={@JoinColumn(name="workgroup_id", referencedColumnName="id")},
   * inverseJoinColumns={@JoinColumn(name="event_id", referencedColumnName="id")})
   */
  private $events;

  /*
  * @OneToMany(targetEntity="data_query", mappedBy="work_type")
  */
  private $queries;

  public static $statuses = ['active', 'deactive'];


  public function __construct(){
    $this->works = new ArrayCollection();
    $this->events = new ArrayCollection();
  }

  public function add_work(work $work){
    if($this->works->contains($work))
      throw new DomainException('Такая работа уже добавлена в группу.');
    $this->works->add($work);
  }

  public function add_event(event $event){
    if($this->events->contains($event))
      throw new DomainException('Событие уже добавлено.');
    $this->events->add($event);
  }

  public function exclude_event(event $event){
    $this->events->removeElement($event);
  }

  public function exclude_work(work $work){
    $this->works->removeElement($work);
  }

  public function get_events(){
    return $this->events;
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