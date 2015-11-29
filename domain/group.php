<?php namespace domain;

use DomainException;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity
* @Table(name="groups")
*/
class group{

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
  * @ManyToMany(targetEntity="domain\user")
  * @JoinTable(name="group2user")
  */
  private $users;

  private static $statuses = ['false', 'true'];

  public function __construct(){
    $this->status = 'true';
    $this->users = new ArrayCollection();
  }

  public function add_user(user $user){
    if($this->users->contains($user))
      throw new DomainException('Пользователь уже добавлен в группу.');
    $this->users->add($user);
  }

  public function exclude_user(user $user){
    if(!$this->users->contains($user))
      throw new DomainException('Пользователя нет в группе.');
    $this->users->removeElement($user);
  }

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public function get_status(){
    return $this->status;
  }

  public function get_users(){
    return $this->users;
  }

  public function set_name($name){
    if(!preg_match('/^[0-9а-яА-Я -]{1,50}$/u', $name))
      throw new DomainException('Название группы задано не верно.');
    $this->name = $name;
  }

  public static function new_instance($name){
    $group = new self();
    $group->set_name($name);
    return $group;
  }
}