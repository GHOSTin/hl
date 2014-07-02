<?php

class data_task {

  private $id;
  private $title;
  private $description;
  private $time_open;
  private $time_close; //время выполнеия
  private $time_target; //дата до которой нужно выполнить задачу
  private $rating = 0;
  private $creator;
  private $performers = [];
  private $comments = [];
  private $reason;
  private $status;

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
    return $this->creator;
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
    $this->performers = $performers;
  }

  /**
   * @return mixed
   */
  public function get_performers()
  {
    return $this->performers;
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