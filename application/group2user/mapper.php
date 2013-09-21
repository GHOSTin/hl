<?php
class mapper_group2user{

  private $company;
  private $group;

  public function __construct(data_company $company, data_group $group){
    $this->company = $company;
    $this->group = $group;
    $this->company->verify('id');
    $this->group->verify('id');
  }

  public function create_object(array $row){
    $user = new data_user();
    $user->set_id($row['id']);
    $user->set_firstname($row['firstname']);
    $user->set_lastname($row['lastname']);
    $user->set_middlename($row['middlename']);
    return $user;
  }

  public function insert(data_user $user){
    $user->verify('id');
    $sql = new sql();
    $sql->query("INSERT INTO `group2user` (`group_id`, `user_id`)
          VALUES (:group_id, :user_id)");
    $sql->bind(':group_id', (int) $this->group->get_id(), PDO::PARAM_INT);
    $sql->bind(':user_id', (int) $user->get_id(), PDO::PARAM_INT);
    $sql->execute('Ошибка при добавлении пользователя в группу.');
  }

  public function delete(data_user $user){
    $user->verify('id');
    $sql = new sql();
    $sql->query("DELETE FROM `group2user` WHERE `group_id` = :group_id
          AND `user_id` = :user_id");
    $sql->bind(':group_id', $this->group->get_id(), PDO::PARAM_INT);
    $sql->bind(':user_id', $user->get_id(), PDO::PARAM_INT);
    $sql->execute('Ошибка при исключении пользователя из группы.');
  }

  public function get_users(){
    $sql = new sql();
    $sql->query("SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
          `users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
          `users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
          `users`.`cellphone`
          FROM `users`, `group2user` WHERE `group2user`.`group_id` = :group_id
          AND `users`.`id` = `group2user`.`user_id` ORDER BY `users`.`lastname`");
    $sql->bind(':group_id', (int) $this->group->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборки пользователей группы.');
    $stmt = $sql->get_stm();
    $users = [];
    while($row = $stmt->fetch()){
      $user = $this->create_object($row);
      $users[$user->get_id()] = $user;
    }
    return $users;
  }

  public function init_users(){
    $users = $this->get_users();
    if(!empty($users))
      foreach($users as $user)
        $this->group->add_user($user);
  }

  public function update(){
    $new = $this->group->get_users();
    $old = $this->get_users();
    $deleted = array_diff_key($old, $new);
    $inserted = array_diff_key($new, $old);
    if(!empty($inserted))
      foreach($inserted as $user)
        $this->insert($user);
    if(!empty($deleted))
      foreach($deleted as $user)
        $this->delete($user);
    return $this->group;
  }
}