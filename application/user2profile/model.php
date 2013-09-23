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

  public function delete($profile){
    $profile = new data_profile($profile);
    $mapper = new mapper_user2profile($this->company, $this->user);
    $mapper->delete($profile);
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

  public function update_rule($profile, $rule){
    $profile = $this->get_profile($profile);
    $rules = $profile->get_rules()->get_rules();
    if(in_array($rule, array_keys($rules))){
      $rules[$rule] = !$rules[$rule];
      $sql = new sql();
      $sql->query('UPDATE `profiles` SET `rules` = :rules WHERE `company_id` = :company_id
        AND `user_id` = :user_id AND `profile` = :profile');
      $sql->bind(':rules', (string) json_encode($rules), PDO::PARAM_STR);
      $sql->bind(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
      $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
      $sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
      $sql->execute('Проблема при обновлении правила.');
    }else
      throw new e_model('Правила '.$rule.' нет в профиле '.$profile);
    return $rules[$rule];
  }
}