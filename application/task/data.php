<?php
use Doctrine\Common\Collections\Criteria;
use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tasks")
 * @ORM\Entity
 */
class data_task {


  /**
   * @ORM\Id()
   * @ORM\Column(name="id", type="string")
   * @var string
   */
  private $id;
  /**
   * @ORM\Column(name="title", type="string")
   * @var string
   */
  private $title;
  /**
   * @ORM\Column(name="description", type="string")
   * @var string
   */
  private $description;
  /**
   * @ORM\Column(name="time_open", type="integer")
   * @var int
   */
  private $time_open;
  /**
   * @ORM\Column(name="time_close", type="integer")
   * @var int
   */
  private $time_close; //время выполнеия
  /**
   * @ORM\Column(name="time_target", type="integer")
   * @var int
   */
  private $time_target; //дата до которой нужно выполнить задачу
  /**
   * @ORM\Column(name="rating", type="integer")
   * @var int
   */
  private $rating = 0;
  /**
   * @ORM\OneToMany(targetEntity="data_task2user", mappedBy="task", cascade={"persist", "remove"})
   * @var \Doctrine\Common\Collections\ArrayCollection
   */
  private $users;
  /**
   * @var array
   */
  private $comments = [];
  /**
   * @ORM\Column(name="reason", type="string")
   * @var string
   */
  private $reason;
  /**
   * @ORM\Column(name="status", type="string")
   * @var string
   */
  private $status;

  function __construct()
  {
    $this->users = di::get('em')->getRepository('data_task2user')->findBy(array('task_id'=>$this->get_id()));
  }

  /**
   * @param mixed $title
   */
  public function set_title($title)
  {
    $this->title = $title;
  }

  /**
   * @return mixed
   */
  public function get_title()
  {
    return $this->title;
  }


  /**
   * @param Integer $id
   */
  public function set_id($id)
  {
    $this->id = $id;
  }

  /**
   * @return Integer
   */
  public function get_id()
  {
    return $this->id;
  }


  /**
   * @param mixed $comments
   */
  public function set_comments(array $comments)
  {
    $this->comments = $comments;
  }

  /**
   * @return mixed
   */
  public function get_comments()
  {
    return $this->comments;
  }

  /**
   * @param mixed $creator
   */
  public function set_creator(data_user $creator)
  {
    $this->creator = $creator;
  }

  /**
   * @return mixed
   */
  public function get_creator()
  {
    $criteria = Criteria::create()
        ->where(Criteria::expr()->eq("user_type", "creator"));
    return $this->users->matching($criteria)->first();
  }

  /**
   * @param mixed $description
   */
  public function set_description($description)
  {
    $this->description = $description;
  }

  /**
   * @return mixed
   */
  public function get_description()
  {
    return $this->description;
  }

  /**
   * @param mixed $performers
   */
  public function set_performers(array $performers)
  {
    $criteria = Criteria::create()
        ->where(Criteria::expr()->eq("user_type", "performer"));
    return $this->users->matching($criteria);
  }

  /**
   * @return mixed
   */
  public function get_performers()
  {
    $criteria = Criteria::create()
        ->where(Criteria::expr()->eq("user_type", "performer"));
    return $this->users->matching($criteria);
  }

  /**
   * @param mixed $rating
   */
  public function set_rating($rating)
  {
    $this->rating = $rating;
  }

  /**
   * @return mixed
   */
  public function get_rating()
  {
    return $this->rating;
  }

  /**
   * @param mixed $reason
   */
  public function set_reason($reason)
  {
    $this->reason = $reason;
  }

  /**
   * @return mixed
   */
  public function get_reason()
  {
    return $this->reason;
  }

  /**
   * @param mixed $status
   */
  public function set_status($status)
  {
    $this->status = $status;
  }

  /**
   * @return mixed
   */
  public function get_status()
  {
    return $this->status;
  }

  /**
   * @param mixed $time_close
   */
  public function set_time_close($time_close)
  {
    $this->time_close = $time_close;
  }

  /**
   * @return mixed
   */
  public function get_time_close()
  {
    return $this->time_close;
  }

  /**
   * @param mixed $time_open
   */
  public function set_time_open($time_open)
  {
    $this->time_open = $time_open;
  }

  /**
   * @return mixed
   */
  public function get_time_open()
  {
    return $this->time_open;
  }

  /**
   * @param mixed $time_target
   */
  public function set_time_target($time_target)
  {
    $this->time_target = $time_target;
  }

  /**
   * @return mixed
   */
  public function get_time_target()
  {
    return $this->time_target;
  }



}