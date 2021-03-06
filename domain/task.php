<?php namespace domain;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Table(name="tasks")
 * @Entity(repositoryClass="\domain\repositories\task")
 */
class task {


  /**
   * @Id()
   * @Column
   * @var integer
  */
  private $id;

  /**
   * @Column
   * @var string
  */
  private $title;

  /**
   * @Column
   * @var string
  */
  private $description;

  /**
   * @Column(type="integer")
   * @var int
   */
  private $time_open;
  /**
   * @Column(type="integer", nullable=true)
   * @var int
   */
  private $time_close; //время выполнеия
  /**
   * @Column(type="integer")
   * @var int
   */
  private $time_target; //дата до которой нужно выполнить задачу
  /**
   * @Column(type="integer")
   * @var int
   */
  private $rating = 0;
  /**
  * @ManyToOne(targetEntity="domain\user")
  */
  private $creator;

  /**
   * @ManyToMany(targetEntity="domain\user")
   * @JoinTable(name="task2performer")
   */
  private $performers;

  /**
   * @OneToMany(targetEntity="domain\task2comment", mappedBy="task", cascade={"persist", "remove"})
   * @var \Doctrine\Common\Collections\ArrayCollection
   */
  private $comments;

  /**
   * @Column(nullable=true)
   * @var string
   */
  private $reason;

  /**
   * @Column
   * @var string
   */
  private $status;

  public function __construct($id, user $creator, $title, $description, $time_target, $time_open){
    $this->status = 'open';
    $this->id = $id;
    $this->creator = $creator;
    $this->title = $title;
    $this->description = $description;
    $this->time_target = $time_target;
    $this->time_open = $time_open;
    $this->performers = new ArrayCollection();
    $this->comments = new ArrayCollection();
  }

  public function close_task($reason, $time,  $rating){
    $this->reason = $reason;
    $this->rating = (int) substr($rating, -1) + 1;
    $this->time_close = (int) $time;
    $this->status = 'close';
  }

  public function update($title, $description, $reason, $target_time, $time_close, $rating){
    $this->title = $title;
    $this->description = $description;
    $this->time_target = $target_time;
    if($this->status === 'close'){
      $this->reason = $reason;
      $this->time_close = $time_close;
      $this->rating = (int) substr($rating, -1) + 1;
    }
  }

  /**
   * @return mixed
   */
  public function get_title()
  {
    return $this->title;
  }

  /**
   * @return Integer
   */
  public function get_id()
  {
    return $this->id;
  }

  /**
   * @return mixed
   */
  public function get_comments()
  {
    return $this->comments;
  }

  public function get_creator(){
    return $this->creator;
  }

  /**
   * @return mixed
   */
  public function get_description(){
    return $this->description;
  }

  public function add_performer($user){
    $this->performers->add($user);
  }

  public function get_performers(){
    return $this->performers;
  }

  /**
   * @return mixed
   */
  public function get_rating()
  {
    return $this->rating;
  }

  /**
   * @return mixed
   */
  public function get_reason()
  {
    return $this->reason;
  }

  /**
   * @return mixed
   */
  public function get_status()
  {
    return $this->status;
  }

  /**
   * @return mixed
   */
  public function get_time_close()
  {
    return $this->time_close;
  }

  /**
   * @return mixed
   */
  public function get_time_open()
  {
    return $this->time_open;
  }

  /**
   * @return mixed
   */
  public function get_time_target()
  {
    return $this->time_target;
  }
}