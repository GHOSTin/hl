<?php
class mapper_query2user{

  private $company;
  private $query;
  private $pdo;

  private static $alert = 'Проблема в мапере соотношения заявки и пользователя.';

  private static $classes = ['creator', 'manager', 'performer', 'observer'];

  private static $all = "SELECT `query2user`.`query_id`,
    `query2user`.`class`, `users`.`id`,
    `users`.`firstname`, `users`.`lastname`, `users`.`midlename`
    FROM `query2user`, `users`
    WHERE `query2user`.`company_id` = :company_id
    AND `users`.`id` = `query2user`.`user_id`
    AND `query2user`.`query_id` = :query_id";

  private static $insert = "INSERT INTO `query2user`
    (`query_id`, `user_id`, `company_id`, `class`)
    VALUES (:query_id, :user_id, :company_id, :class)";

  private static $delete = "DELETE FROM `query2user`
    WHERE `company_id` = :company_id AND `query_id` = :query_id
    AND `user_id` = :user_id AND `class` = :class";

  public function __construct($company, $query){
    $this->company = $company;
    $this->query = $query;
    data_company::verify_id($this->company->get_id());
    data_query::verify_id($this->query->get_id());
    $this->pdo = di::get('pdo');
  }

  public function create_object(array $row){
    $user = new data_user();
    $user->set_id($row['id']);
    $user->set_firstname($row['firstname']);
    if(!empty($row['middlename']))
      $user->set_middlename($row['middlename']);
    $user->set_lastname($row['lastname']);
    return $user;
  }

  private function get_users(){
    $stmt = $this->pdo->prepare(self::$all);
    $stmt->bindValue(':query_id', (int) $this->query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $users = ['creator' => null, 'manager' => [],
              'observer' => [], 'performer' => []];
    while($row = $stmt->fetch()){
      $user = $this->create_object($row);
      $users[$row['class']][$user->get_id()] = $user;
    }
    $stmt->closeCursor();
    return $users;
  }

  /*
  * Зависимая функция.
  * Добавляет ассоциацию заявка-пользователь.
  */
  private function insert(data_user $user, $class){
    if(!in_array($class, self::$classes, true))
      throw new e_model('Не соответсвует тип пользователя.');
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':query_id', $this->query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':class', $class, PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
  }

  /**
  * Удаляет пользователя из заявки.
  */
  public function delete(data_user $user, $class){
    if(!in_array($class, self::$classes, true))
      throw new e_model('Не соответсвует тип пользователя.');
    $stmt = $this->pdo->prepare(self::$delete);
    $stmt->bindValue(':query_id', $this->query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':class', $class, PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
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
      $users = $this->get_users();
      $new_managers = $this->query->get_managers();
      $delete_managers = array_diff_key($users['manager'], $new_managers);
      $insert_managers = array_diff_key($new_managers, $users['manager']);
      if(!empty($insert_managers))
          foreach($insert_managers as $user)
              $this->insert($user, 'manager');
      if(!empty($delete_managers))
          foreach($delete_managers as $number)
              $this->delete($number, 'manager');

      $new_performers = $this->query->get_performers();
      $delete_performers = array_diff_key($users['performer'], $new_performers);
      $insert_performers = array_diff_key($new_performers, $users['performer']);
      if(!empty($insert_performers))
          foreach($insert_performers as $user)
              $this->insert($user, 'performer');
      if(!empty($delete_performers))
          foreach($delete_performers as $number)
              $this->delete($number, 'performer');

      $new_observers = $this->query->get_observers();
      $delete_observers = array_diff_key($users['observer'], $new_observers);
      $insert_observers = array_diff_key($new_observers, $users['observer']);
      if(!empty($insert_observers))
          foreach($insert_observers as $user)
              $this->insert($user, 'observer');
      if(!empty($delete_observers))
          foreach($delete_observers as $number)
              $this->delete($number, 'observer');
      if(empty($users['creator']))
        if(empty($this->query->get_creator()))
          throw new e_model('Создатель заявки не может быть пустым.');
        else
          $this->insert($this->query->get_creator(), 'creator');
      return $query;
  }
}