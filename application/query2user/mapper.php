<?php
class mapper_query2user{

  private $company;
  private $query;

  private static $sql_get_users = "SELECT `query2user`.`query_id`,
    `query2user`.`class`, `users`.`id`,
    `users`.`firstname`, `users`.`lastname`, `users`.`midlename`
    FROM `query2user`, `users`
    WHERE `query2user`.`company_id` = :company_id
    AND `users`.`id` = `query2user`.`user_id`
    AND `query2user`.`query_id` = :query_id";

  public function __construct($company, $query){
    $this->company = $company;
    $this->query = $query;
    $this->company->verify('id');
    $this->query->verify('id');
  }

  public function create_object(array $row){
    $user = new data_user();
    $user->set_id($row['id']);
    $user->set_firstname($row['firstname']);
    $user->set_middlename($row['middlename']);
    $user->set_lastname($row['lastname']);
    $q2u = new data_query2user($user);
    $q2u->set_class($row['class']);
    return $q2u;
  }

  private function get_users(){
    $sql = new sql();
    $sql->query(self::$sql_get_users);
    $sql->bind(':query_id', (int) $this->query->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при запросе связи заявка-лицевой_счет.');
    $stmt = $sql->get_stm();
    $users = [];
    while($row = $stmt->fetch())
      $users[] = $this->create_object($row);
    $stmt->closeCursor();
    return $users;
  }

  public function init_users(){
    $users = $this->get_users();
    if(!empty($users))
      foreach($users as $user){
        if($user->get_class() === 'creator')
          $this->query->add_creator($user);
        if($user->get_class() === 'manager')
          $this->query->add_manager($user);
        if($user->get_class() === 'performer')
          $this->query->add_performer($user);
        if($user->get_class() === 'observer')
          $this->query->add_observer($user);
      }
    return $this->query;
  }
}