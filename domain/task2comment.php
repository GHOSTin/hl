<?php namespace domain;
/**
 * Class \domain\task2comment
 * @Entity
 * @Table(name="task2comment")
 */
class task2comment {

  /**
   * @\Doctrine\ORM\Mapping\Id()
   * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
   * @\Doctrine\ORM\Mapping\Column(name="id")
   * @var
   */
  private $id;
  /**
   * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="\domain\task", inversedBy="comments")
   * @\Doctrine\ORM\Mapping\JoinColumn(name="task_id", referencedColumnName="id")
   */
  private $task;
  /**
   * @\Doctrine\ORM\Mapping\Column(name="message", type="string")
   * @var string
   */
  private $message;
  /**
   * @\Doctrine\ORM\Mapping\Column(name="time", type="integer")
   * @var int
   */
  private $time;
  /**
   * @\Doctrine\ORM\Mapping\OneToOne(targetEntity="\domain\user")
   * @\Doctrine\ORM\Mapping\JoinColumn(name="user_id", referencedColumnName="id")
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