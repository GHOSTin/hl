<?php
class model_user2profile{

  private $company;
  private $user;

  public function __construct(data_company $company, data_user $user){
    $this->company = $company;
    $this->user = $user;
    $this->company->verify('id');
    $this->user->verify('id');
  }

  public function get_profiles(){
    $mapper = new mapper_user2profile($this->company, $this->user);
    return $mapper->find_all();
  }

  public function get_profile($name){
    $mapper = new mapper_user2profile($this->company, $this->user);
    $profile = $mapper->find($name);
    if(!($profile instanceof data_profile))
      throw new e_model('Нет профиля.');
    return $profile;
  }
}