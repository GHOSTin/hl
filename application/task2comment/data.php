<?php

class data_task2comment {

  private $message;
  private $time;
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
  public function set_user(data_user $user)
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

} 