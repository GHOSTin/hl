<?php


/**
 * @Entity
 * @\Doctrine\ORM\Mapping\Table(name="task2user")
 * Class data_task2user
 */
class data_task2user {

  /**
   * @\Doctrine\ORM\Mapping\Id()
   * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
   * @\Doctrine\ORM\Mapping\Column(name="id", type="integer")
   */
  private $id;

  /**
   * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="data_task", inversedBy="users")
   * @\Doctrine\ORM\Mapping\JoinColumn(name="task_id", referencedColumnName="id")
   */
  private $task;
  /**
   * @\Doctrine\ORM\Mapping\OneToOne(targetEntity="data_user")
   * @\Doctrine\ORM\Mapping\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;
  /**
   * @\Doctrine\ORM\Mapping\Column(name="user_type", type="string")
   */
  public $user_type;

  /**
   * @return mixed
   */
  public function get_id()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function set_id($id)
  {
    $this->id = $id;
  }

  /**
   * @return data_task
   */
  public function get_task()
  {
    return $this->task;
  }

  /**
   * @param data_task $task
   */
  public function set_task($task)
  {
    $this->task = $task;
  }

  /**
   * @return data_user
   */
  public function get_user()
  {
    return $this->user;
  }

  /**
   * @param data_user $user
   */
  public function set_user($user)
  {
    $this->user = $user;
  }

  /**
   * @return string
   */
  public function get_user_type()
  {
    return $this->user_type;
  }

  /**
   * @param string $user_type
   */
  public function set_user_type($user_type)
  {
    $this->user_type = $user_type;
  }

} 