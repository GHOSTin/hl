<?php namespace domain;

/**
 * @Entity
 * @\Doctrine\ORM\Mapping\Table(name="task2user")
 * Class \domain\task2user
 */
class task2user {

  /**
   * @\Doctrine\ORM\Mapping\Id()
   * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
   * @\Doctrine\ORM\Mapping\Column(name="id", type="integer")
   */
  private $id;

  /**
   * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="\domain\task", inversedBy="users")
   * @\Doctrine\ORM\Mapping\JoinColumn(name="task_id", referencedColumnName="id")
   */
  private $task;
  /**
   * @\Doctrine\ORM\Mapping\OneToOne(targetEntity="\domain\user")
   * @\Doctrine\ORM\Mapping\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;
  /**
   * @\Doctrine\ORM\Mapping\Column(name="user_type", type="string")
   */
  public $userType;

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
   * @return \domain\task
   */
  public function get_task()
  {
    return $this->task;
  }

  /**
   * @param \domain\task $task
   */
  public function set_task($task)
  {
    $this->task = $task;
  }

  /**
   * @return \domain\user
   */
  public function get_user()
  {
    return $this->user;
  }

  /**
   * @param \domain\user $user
   */
  public function set_user($user)
  {
    $this->user = $user;
  }

  /**
   * @return string
   */
  public function get_userType()
  {
    return $this->userType;
  }

  /**
   * @param string $userType
   */
  public function set_userType($userType)
  {
    $this->userType = $userType;
  }

}