<?php
/** @Entity
*    @Table(name="users")
*/
class data_user extends data_object{

  /**
  * @Column(name="cellphone", type="string")
  */
	private $cellphone;

  /**
  * @Column(name="company_id", type="integer")
  */
	private $company_id;
  /**
  * @Column(name="firstname", type="string")
  */
	private $firstname;

  /**
   * @Id
   * @Column(name="id", type="integer")
   * @GeneratedValue
   */
	private $id;

  /**
  * @Column(name="lastname", type="string")
  */
	private $lastname;

  /**
   * @Column(name="username", type="string")
   */
	private $login;

  /**
  * @Column(name="midlename", type="string")
  */
	private $middlename;
	private $session;

  /**
  * @Column(name="status", type="string")
  */
	private $status;

  /**
  * @Column(name="telephone", type="string")
  */
	private $telephone;

  /**
  * @Column(name="password", type="string")
  */
  private $hash;

  public static $statuses = ['true', 'false'];

  public function get_cellphone(){
    return $this->cellphone;
  }

  public function get_company_id(){
    return $this->company_id;
  }

  public function get_firstname(){
    return $this->firstname;
  }
  public function get_hash(){
    return $this->hash;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_lastname(){
    return $this->lastname;
  }

  public function get_login(){
    return $this->login;
  }

  public function get_middlename(){
    return $this->middlename;
  }

  public function get_status(){
    return $this->status;
  }

  public function get_telephone(){
    return $this->telephone;
  }

  public function set_cellphone($cellphone){
    if(!preg_match('/^[0-9+]{0,11}$/', $cellphone))
      throw new DomainException('Wrong user cellphone '.$cellphone);
    $this->cellphone = $cellphone;
  }

  public function set_id($id){
    if($id > 65535 OR $id < 1)
      throw new DomainException('Идентификатор пользователя задан не верно.');
    $this->id = $id;
  }

  public function set_company_id($id){
    if($id > 255 OR $id < 1)
      throw new DomainException('Wrong company id '.$id);
    $this->company_id = $id;
  }

  public function set_firstname($firstname){
    if(!preg_match('/^[а-яА-Я]{1,255}$/u', $firstname))
      throw new DomainException('Wrong user firstname '.$firstname);
    $this->firstname = $firstname;
  }

  public function set_hash($hash){
    $this->hash = $hash;
  }

  public function set_lastname($lastname){
    if(!preg_match('/^[а-яА-Я]{1,255}$/u', $lastname))
      throw new DomainException('Wrong user lastname '.$lastname);
    $this->lastname = $lastname;
  }

  public function set_login($login){
    if(!preg_match('/^[а-яА-ЯA-Za-z0-9]{3,255}$/u', $login))
      throw new DomainException('Wrong user login '.$login);
    $this->login = $login;
  }

  public function set_middlename($middlename){
    if(!preg_match('/^[а-яА-Я]{0,255}$/u', $middlename))
      throw new DomainException('Wrong user middlename '.$middlename);
    $this->middlename = $middlename;
  }

  public function set_status($status){
    if(!in_array($status, self::$statuses, true))
      throw new DomainException('wrong user status '.$status);
    $this->status = $status;
  }

  public function set_telephone($telephone){
    if(!preg_match('/^[0-9]{0,11}$/', $telephone))
      throw new DomainException('Wrong user telephone '.$telephone);
    $this->telephone = $telephone;
  }
}