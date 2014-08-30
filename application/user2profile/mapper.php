<?php
class mapper_user2profile{

  private $user;
  private $pdo;

  private static $many = "SELECT `profile` FROM `profiles`
    WHERE  `user_id` = :user_id AND `company_id` = :company_id";

  private static $one = "SELECT `profile`, `rules`, `restrictions`, `settings`
    FROM `profiles` WHERE  `user_id` = :user_id
    AND `profile` = :profile";

  private static $delete = "DELETE FROM `profiles` WHERE  `user_id` = :user_id
    AND `company_id` = :company_id AND `profile` = :profile";

  private static $insert = "INSERT INTO `profiles` (`company_id`, `user_id`,
    `profile`, `rules`, `restrictions`, `settings`) VALUES (:company_id,
    :user_id, :profile, :rules, :restrictions, :settings)";

  public function __construct( data_user $user){
    $this->user = $user;
    $this->pdo = di::get('pdo');
  }

  public function create_object(array $row){
    $profile = new data_profile($row['profile']);
    $profile->set_rules((array) json_decode($row['rules']));
    $profile->set_restrictions((array) json_decode($row['restrictions']));
    return $profile;
  }

  public function delete(data_profile $profile){
    $stmt = $this->pdo->prepare(self::$delete);
    $stmt->bindValue(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':profile', (string) $profile, PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
  }

  public function find_all(){
    $stmt = $this->pdo->prepare(self::$many);
    $stmt->bindValue(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $profiles = [];
    if($stmt->rowCount() > 0)
      while($row = $stmt->fetch())
        $profiles[] = $this->create_object($row);
    return $profiles;
  }

  public function find($profile){
    $stmt = $this->pdo->prepare(self::$one);
    $stmt->bindValue(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':profile', (string) $profile, PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new RuntimeException();
  }

  public function insert(data_profile $profile){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':profile', (string) $profile, PDO::PARAM_STR);
    $stmt->bindValue(':rules', json_encode($profile->get_rules()), PDO::PARAM_STR);
    $stmt->bindValue(':restrictions', json_encode($profile->get_restrictions()), PDO::PARAM_STR);
    $stmt->bindValue(':settings', '[]', PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
  }
}