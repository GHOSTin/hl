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

  private static $sql_insert = "INSERT INTO `query2user`
    (`query_id`, `user_id`, `company_id`, `class`)
    VALUES (:query_id, :user_id, :company_id, :class)";

  private static $sql_delete = "DELETE FROM `query2user`
    WHERE `company_id` = :company_id AND `query_id` = :query_id
    AND `user_id` = :user_id AND `class` = :class";

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
    return $user;
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
      $users[$row['class']][] = $this->create_object($row);
    $stmt->closeCursor();
    return $users;
  }

  /*
  * Зависимая функция.
  * Добавляет ассоциацию заявка-пользователь.
  */
  private function insert($user){
    if(!in_array($user->get_class(), ['creator', 'observer', 'manager', 'performer'], true))
      throw new e_model('Не соответсвует тип пользователя.');
    $sql = new sql();
    $sql->query(self::$sql_insert);
    $sql->bind(':query_id', $this->query->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':user_id', $user->get_id(), PDO::PARAM_INT);
    $sql->bind(':class', $user->get_class(), PDO::PARAM_STR);
    $sql->execute('Проблема при добавлении пользователя.');
  }

  /**
  * Удаляет пользователя из заявки.
  */
  public function delete(data_query2user $user){
    if(!in_array($user->get_class(), ['manager', 'performer'], true))
      throw new e_model('Несоответствующие параметры: class.');
    $sql = new sql();
    $sql->query(self::$sql_delete);
    $sql->bind(':query_id', $this->query->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':user_id', $user->get_id(), PDO::PARAM_INT);
    $sql->bind(':class', $user->get_class(), PDO::PARAM_STR);
    $sql->execute('Ошибка при удалении пользователя и заявки.');
    return $query;
  }

  public function init_users(){
    $users = $this->get_users();
    if(!empty($users['creator']))
      foreach($users['creator'] as $user){
          $this->query->add_creator($user);
      }
    if(!empty($users['manager']))
      foreach($users['manager'] as $user){
          $this->query->add_manager($user);
      }
    if(!empty($users['performer']))
      foreach($users['performer'] as $user){
          $this->query->add_performer($user);
      }
    if(!empty($users['observer']))
      foreach($users['observer'] as $user){
          $this->query->add_observer($user);
      }
    return $this->query;
  }



  public function update_users(){
    exit();
    $users = $this->get_users();
    $old_creators = [];
    $old_managers = [];
    $old_performers = [];
    $old_observers = [];
    if(!empty($users))
      foreach($users as $user){
        if($user->get_class() === 'creator')
          $old_creators[$user->get_id()] = $user;
        if($user->get_class() === 'manager')
          $old_managers[$user->get_id()] = $user;
        if($user->get_class() === 'performer')
          $old_performers[$user->get_id()] = $user;
        if($user->get_class() === 'observer')
          $old_observers[$user->get_id()] = $user;
      }

    $new_managers = $this->query->get_managers();
    $delete_managers = array_diff_key($old_managers, $new_managers);
    $insert_managers = array_diff_key($new_managers, $old_managers);
    if(!empty($insert_managers))
        foreach($insert_managers as $user)
            $this->insert($user);
    if(!empty($delete_managers))
        foreach($delete_managers as $number)
            $this->delete($number);

    $new_performers = $this->query->get_performers();
    $delete_performers = array_diff_key($old_performers, $new_performers);
    $insert_performers = array_diff_key($new_performers, $old_performers);
    if(!empty($insert_performers))
        foreach($insert_performers as $user)
            $this->insert($user);
    if(!empty($delete_performers))
        foreach($delete_performers as $number)
            $this->delete($number);

    $new_observers = $this->query->get_observers();
    $delete_observers = array_diff_key($old_observers, $new_observers);
    $insert_observers = array_diff_key($new_observers, $old_observers);
    if(!empty($insert_observers))
        foreach($insert_observers as $user)
            $this->insert($user);
    if(!empty($delete_observers))
        foreach($delete_observers as $number)
            $this->delete($number);
    if(empty($old_creators))
      if(empty($this->query->get_creator()))
        throw new e_model('Создатель заявки не может быть пустым.');
      else
        $this->insert($this->query->get_creator());
    return $query;
  }
}