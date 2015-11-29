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
   * @Column(type="integer")
   * @GeneratedValue
   */
  private $id;

  /**
  * @Column
  */
  private $name;

  /**
  * @Column
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

  /**
  * @OneToMany(targetEntity="domain\phrase", mappedBy="workgroup")
  */
  private $phrases;

  public function __construct(){
    $this->works = new ArrayCollection();
    $this->events = new ArrayCollection();
    $this->phrases = new ArrayCollection();
    $this->status = 'active';
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

  public function get_phrases(){
    return $this->phrases;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public static function new_instance($name){
    $workgroup = new self();
    $workgroup->set_name($name);
    return $workgroup;
  }

  public function set_name($name){
    if(!preg_match('/^[а-яА-Я -]{1,255}$/u', $name))
      throw new DomainException('Wrong work name'.$name);
    $this->name = $name;
  }
}