<?php namespace domain;

use DomainException;
use JsonSerializable;

/**
* @Entity
* @Table(name="files")
*/
class file implements interfaces\file, JsonSerializable{

  /**
  * @Column(type="integer")
  */
  private $time;

  /**
  * @Column(length=255)
  */
  private $name;

  /**
  * @Id
  * @Column(length=255, unique=true)
  */
  private $path;

  /**
  * @ManyToOne(targetEntity="domain\user")
  */
  private $user;

  public function __construct(user $user, $path, $time, $name){
    $this->set_path($path);
    $this->set_time($time);
    $this->set_name($name);
    $this->user = $user;
  }

  public function get_name(){
    return $this->name;
  }

  public function get_path(){
    return $this->path;
  }

  public function get_time(){
    return $this->time;
  }

  public function get_user(){
    return $this->user;
  }

  public function set_name($name){
    if(strlen($name) > 255)
      throw new DomainException('Wrong name');
    $this->name = $name;
  }

  public function set_path($path){
    if(strlen($path) > 255)
      throw new DomainException('Wrong path');
    $this->path = $path;
  }

  public function set_time($time){
    $this->time = $time;
  }

  public function JsonSerialize(){
    return [
             'date' => $this->time,
             'path' => $this->path,
             'name' => $this->name,
           ];
  }
}