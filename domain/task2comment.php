<?php namespace domain;
/**
 * @Entity
 */
class task2comment {

  /**
   * @Id()
   * @Column(type="integer")
   * @GeneratedValue
   *
   * @var int
   */
  private $id;

  /**
   * @ManyToOne(targetEntity="domain\task", inversedBy="comments")
   */
  private $task;

  /**
   * @Column
   * @var string
   */
  private $message;

  /**
   * @Column(type="integer")
   * @var int
   */
  private $time;

  /**
   * @ManyToOne(targetEntity="domain\user")
   */
  private $user;

  /**
   * @param mixed $message
   */
  public function set_message($message)
  {
    $this->message = $message;
  }

  /**
   * @return mixed
   */
  public function get_message()
  {
    return $this->message;
  }

  /**
   * @param mixed $time
   */
  public function set_time($time)
  {
    $this->time = $time;
  }

  /**
   * @return mixed
   */
  public function get_time()
  {
    return $this->time;
  }

  /**
   * @param mixed $user
   */
  public function set_user(\domain\user $user)
  {
    $this->user = $user;
  }

  /**
   * @return mixed
   */
  public function get_user()
  {
    return $this->user;
  }

  /**
   * @param mixed $task
   */
  public function set_task($task)
  {
    $this->task = $task;
  }

  /**
   * @return mixed
   */
  public function get_task()
  {
    return $this->task;
  }
}