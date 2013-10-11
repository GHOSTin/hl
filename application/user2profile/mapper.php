<?php
class mapper_user2profile{

  private $company;
  private $user;

  private static $sql_find_all = "SELECT `profile` FROM `profiles` 
          WHERE  `user_id` = :user_id AND `company_id` = :company_id";

  private static $sql_find = "SELECT `profile`, `rules`, `restrictions`, `settings`
    FROM `profiles` WHERE  `user_id` = :user_id 
    AND `company_id` = :company_id AND `profile` = :profile";

  private static $sql_delete = "DELETE FROM `profiles` WHERE  `user_id` = :user_id
          AND `company_id` = :company_id AND `profile` = :profile";

  public function __construct(data_company $company, data_user $user){
    $this->company = $company;
    $this->user = $user;
    $this->company->verify('id');
    $this->user->verify('id');
  }

  public function create_object(array $row){
    $profile = new data_profile($row['profile']);
    $rules = (array) json_decode($row['rules']);
    $rule_collection = new data_rule_collection();
    if(!empty($rules))
      foreach($rules as $rule => $status)
        $rule_collection->add_rule($rule, $status);
    $profile->set_rules($rule_collection);
    $profile->set_restrictions((array) json_decode($row['restrictions']));
    return $profile;
  }

  public function delete(data_profile $profile){
    $sql = new sql();
    $sql->query(self::$sql_delete);
    $sql->bind(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
    $sql->execute('Ошибка при удалении профиля.');
  }

  public function find_all(){
    $sql = new sql();
    $sql->query(self::$sql_find_all);
    $sql->bind(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Ошибка при получении профиля.');
    $profiles = [];
    $stmt = $sql->get_stm();
    if($stmt->rowCount() > 0)
      while($row = $stmt->fetch())
        $profiles[] = $this->create_object($row);
    $sql->close();
    return $profiles;
  }

  public function find($profile){
    $sql = new sql();
    $sql->query(self::$sql_find);
    $sql->bind(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
    $sql->execute('Ошибка при получении профиля.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неверное количество профилей.');     
  }
}