<?php
/**
* @Entity
* @Table(name="groups")
*/
class data_group extends data_object{

  /**
  * @Column(name="company_id", type="string")
  */
	private $company_id;

  /**
  * @Id
  * @Column(name="id", type="integer")
  * @GeneratedValue
  */
  private $id;

  /**
  * @Column(name="name", type="string")
  */
  private $name;

  /**
  * @Column(name="status", type="string")
  */
  private $status;

  /**
   * @ManyToMany(targetEntity="data_user")
   * @JoinTable(name="group2user",
   * joinColumns={@JoinColumn(name="group_id", referencedColumnName="id")},
   * inverseJoinColumns={@JoinColumn(name="user_id", referencedColumnName="id")})
   */
  private $users;

  private static $statuses = ['false', 'true'];

  public function add_user(data_user $user){
    if($this->users->contains($user))
      throw new DomainException('Пользователь уже добавлен в группу.');
    $this->users[] = $user;
  }

  public function exclude_user(data_user $user){
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

  public function set_company_id($id){
    return $this->company_id = $id;
  }

  public function set_id($id){
    if($id > 65535 OR $id < 1)
      throw new DomainException('Идентификатор группы задан не верно.');
    $this->id = $id;
  }

  public function set_name($name){
    if(!preg_match('/^[0-9а-яА-Я ]{1,50}$/u', $name))
      throw new DomainException('Название группы задано не верно.');
    $this->name = $name;
  }

  public function set_status($status){
    if(!in_array($status, self::$statuses))
      throw new DomainException('Статус группы задан не верно.');
    $this->status = (string) $status;
  }
}