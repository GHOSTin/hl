  <?php
  class mapper_user{

  private static $LOGIN = "SELECT `id`, `company_id`, `status`,
    `username` as `login`, `firstname`, `lastname`,
    `midlename` as `middlename`, `password`, 
    `telephone`, `cellphone` FROM `users` WHERE `username` = :login
    AND `password` = :hash";
  private static $find = "SELECT `id`, `company_id`, `status`,
    `username` as `login`, `firstname`, `lastname`, `midlename` as `middlename`,
    `password`, `telephone`, `cellphone` FROM `users` WHERE `id` = :id";
  private static $insert_id = "SELECT MAX(`id`) as `max_user_id` FROM `users`";
  private static $update = "UPDATE `users` SET `firstname` = :firstname,
    `lastname` = :lastname, `midlename` = :middlename, `status` = :status,
    `password` = :password, `telephone` = :telephone, `cellphone` = :cellphone,
    `username` = :login WHERE `id` = :id";
  private static $insert = "INSERT INTO `users` (`id`, `company_id`, `status`,
    `username`, `firstname`, `lastname`, `midlename`, `password`, `telephone`,
    `cellphone`) VALUES (:user_id, :company_id, :status, :login, :firstname,
    :lastname, :middlename, :password, :telephone, :cellphone)";
  private static $get_users = "SELECT `id`, `company_id`, `status`,
    `username` as `login`, `firstname`, `lastname`, `midlename` as `middlename`,
    `password`, `telephone`, `cellphone` FROM `users` ORDER BY `lastname`";

  public function create_object(array $row){
    $user = new data_user();
    $user->set_id($row['id']);
    $user->set_company_id($row['company_id']);
    $user->set_status($row['status']);
    $user->set_login($row['login']);
    $user->set_firstname($row['firstname']);
    $user->set_lastname($row['lastname']);
    $user->set_middlename($row['middlename']);
    $user->set_hash($row['password']);
    $user->set_telephone($row['telephone']);
    $user->set_cellphone($row['cellphone']);
    return $user;
  }

  public function find($id){
    $sql = new sql();
    $sql->query(self::$find);
    $sql->bind(':id', (int) $id, PDO::PARAM_INT);
    $sql->execute('Проблема при выборке пользователя.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    if($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное количество пользователей.');
  }

  public function find_by_login_and_password($login, $password){
    try{
      $sql = new sql();
      $sql->query(self::$LOGIN);
      $sql->bind(':login', htmlspecialchars($login), PDO::PARAM_STR);
      $sql->bind(':hash', (new model_user)->get_password_hash($password) , PDO::PARAM_STR);
      $sql->execute('Проблема при авторизации');
      $stm = $sql->get_stm();
      if($stm->rowCount() !== 1){
          $sql->close();
          throw new e_model('Проблемы при авторизации.');
      }
      $user = $this->create_object($stm->fetch());
      $sql->close();
      return $user;
    }catch(exception $e){
        return null;
    }
  }

  private function get_insert_id(){
    $sql = new sql();
    $sql->query(self::$insert_id);
    $sql->execute('Проблема при опредении следующего user_id.');
    if($sql->count() !== 1)
        throw new e_model('Проблема при опредении следующего user_id.');
    $user_id = (int) $sql->row()['max_user_id'] + 1;
    $sql->close();
    return $user_id;
  }

  /**
  * Возвращает пользователей
  * @return array из data_user
  */
  public function get_users(){
    $sql = new sql();
    $sql->query(self::$get_users);
    $sql->execute('Проблема при выборке пользователей.');
    $stmt = $sql->get_stm();
    $users = [];
    while($row = $stmt->fetch())
      $users[] = $this->create_object($row);
    return $users;
  }

  public function insert(data_user $user){
    $sql = new sql();
    $sql->query("SELECT `id` FROM `users` WHERE `username` = :login");
    $sql->bind(':login', $user->get_login(), PDO::PARAM_STR);
    $sql->execute("Ошибка при поиске идентичного логина.");
    if($sql->count() !== 0)
        throw new e_model('Пользователь с таким логином уже существует.');
    $user->set_id($this->get_insert_id());
    $user->verify('firstname', 'lastname', 'middlename', 'login', 'status');
    $sql = new sql();
    $sql->query(self::$insert);
    $sql->bind(':user_id', $user->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', 2, PDO::PARAM_INT);
    $sql->bind(':status', $user->get_status(), PDO::PARAM_STR);
    $sql->bind(':login', $user->get_login(), PDO::PARAM_STR);
    $sql->bind(':firstname', $user->get_firstname(), PDO::PARAM_STR);
    $sql->bind(':lastname', $user->get_lastname(), PDO::PARAM_STR);
    $sql->bind(':middlename', $user->get_middlename(), PDO::PARAM_STR);
    $sql->bind(':password', $user->get_hash(), PDO::PARAM_STR);
    $sql->bind(':telephone', $user->get_telephone(), PDO::PARAM_STR);
    $sql->bind(':cellphone', $user->get_cellphone(), PDO::PARAM_STR);
    $sql->execute('Проблемы при создании пользователя.');
    return $user;
  }

  public function update(data_user $user){
    $user->verify('id', 'firstname', 'middlename', 'lastname', 'status',
      'login', 'telephone');
    $sql = new sql();
    $sql->query(self::$update);
    $sql->bind(':firstname', $user->get_firstname(), PDO::PARAM_STR);
    $sql->bind(':lastname', $user->get_lastname(), PDO::PARAM_STR);
    $sql->bind(':middlename', $user->get_middlename(), PDO::PARAM_STR);
    $sql->bind(':status', $user->get_status(), PDO::PARAM_STR);
    $sql->bind(':login', $user->get_login(), PDO::PARAM_STR);
    $sql->bind(':telephone', $user->get_telephone(), PDO::PARAM_STR);
    $sql->bind(':cellphone', $user->get_cellphone(), PDO::PARAM_STR);
    $sql->bind(':password', $user->get_hash(), PDO::PARAM_STR);
    $sql->bind(':id', $user->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблемы при обвнолении записи пользователя.');
  }
}