<?php
class mapper_user2profile{

  private $company;
  private $user;

  public function __construct(data_company $company, data_user $user){
    $this->company = $company;
    $this->user = $user;
    $this->company->verify('id');
    $this->user->verify('id');
  }

  public function find_all(){
    $sql = new sql();
    $sql->query("SELECT `profile` FROM `profiles` 
          WHERE  `user_id` = :user_id AND `company_id` = :company_id");
    $sql->bind(':user_id', $this->user->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Ошибка при получении профиля.');
    $profiles = [];
    if($sql->count() > 0)
      while($profile = $sql->row())
        $profiles[] = $profile['profile'];
    $sql->close();
    return $profiles;
  }
}