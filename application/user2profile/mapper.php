<?php
class mapper_user2profile{

  private $company;
  private $user;

  private static $many = "SELECT `profile` FROM `profiles` 
    WHERE  `user_id` = :user_id AND `company_id` = :company_id";

  private static $one = "SELECT `profile`, `rules`, `restrictions`, `settings`
    FROM `profiles` WHERE  `user_id` = :user_id 
    AND `company_id` = :company_id AND `profile` = :profile";

  private static $delete = "DELETE FROM `profiles` WHERE  `user_id` = :user_id
    AND `company_id` = :company_id AND `profile` = :profile";

  private static $insert = "INSERT INTO `profiles` (`company_id`, `user_id`,
    `profile`, `rules`, `restrictions`, `settings`) VALUES (:company_id,
    :user_id, :profile, :rules, :restrictions, :settings)";

  public function __construct(data_company $company, data_user $user){
    $this->company = $company;
    $this->user = $user;
    data_company::verify_id($this->company->get_id());
    data_user::verify_id($this->user->get_id());
  }

  public function create_object(array $row){
    $profile = new data_profile($row['profile']);
    $profile->set_rules((array) json_decode($row['rules']));
    $profile->set_restrictions((array) json_decode($row['restrictions']));
    return $profile;
  }

  public function delete(data_profile $profile){
    $sql = new sql();
    $sql->query(self::$delete);
    $sql->bind(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
    $sql->execute('Ошибка при удалении профиля.');
  }

  public function find_all(){
    $sql = new sql();
    $sql->query(self::$many);
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
    $sql->query(self::$one);
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

  public function insert(data_profile $profile){
    $sql = new sql();
    $sql->query(self::$insert);
    $sql->bind(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
    $sql->bind(':rules', json_encode($profile->get_rules()), PDO::PARAM_STR);
    $sql->bind(':restrictions', json_encode($profile->get_restrictions()), PDO::PARAM_STR);
    $sql->bind(':settings', '[]', PDO::PARAM_STR);
    $sql->execute('Ошибка при добавлении профиля.');
  }
}