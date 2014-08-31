<?php
/**
* @Entity
* @Table(name="profiles")
*/
class data_profile extends data_object{

  /**
  * @ManyToOne(targetEntity="data_user")
  */
  private $user;

  /**
  * @Id
  * @Column(name="user_id", type="integer")
  */
  private $user_id;

  /**
  * @Id
  * @Column(name="profile", type="string")
  */
  private $profile;

  /**
  * @Column(name="rules", type="json_array")
  */
  private $rules;

  /**
  * @Column(name="restrictions", type="json_array")
  */
  private $restrictions;

  public function __construct(data_user $user, $profile){
    $this->user = $user;
    $this->user_id = $user->get_id();
    $this->profile = $profile;
  }

  public function __tostring(){
    return $this->profile;
  }

  public function get_rules(){
    return $this->rules;
  }

  public function set_rules(array $rules){
    $this->rules = $rules;
  }

  public function set_restrictions(array $restrictions){
    $this->restrictions = $restrictions;
  }

  public function get_restrictions(){
    return $this->restrictions;
  }
}