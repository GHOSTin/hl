  <?php
  class mapper_user{

  private $pdo;

  private static $LOGIN = "SELECT `id`, `company_id`, `status`,
    `username` as `login`, `firstname`, `lastname`,
    `midlename` as `middlename`, `password`, 
    `telephone`, `cellphone` FROM `users` WHERE `username` = :login
    AND `password` = :hash";

  private static $find = "SELECT `id`, `company_id`, `status`,
    `username` as `login`, `firstname`, `lastname`, `midlename` as `middlename`,
    `password`, `telephone`, `cellphone` FROM `users` WHERE `id` = :id";

  private static $id = "SELECT MAX(`id`) as `max_user_id` FROM `users`";

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

  public function __construct(){
    $this->pdo = di::get('pdo');
  }

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
    $stmt = $this->pdo->prepare(self::$find);
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
    if($stmt->execute())
      throw new e_model($alert);
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
      $stmt = $this->pdo->prepare(self::$LOGIN);
      $stmt->bindValue(':login', htmlspecialchars($login), PDO::PARAM_STR);
      $stmt->bindValue(':hash', (new model_user)->get_password_hash($password) , PDO::PARAM_STR);
      if($stmt->execute())
        throw new e_model($alert);
      if($stm->rowCount() !== 1){
          $stmt->closeCursor();
          throw new e_model('Проблемы при авторизации.');
      }
      $user = $this->create_object($stm->fetch());
      $stmt->closeCursor();
      return $user;
    }catch(exception $e){
        return null;
    }
  }

  private function get_insert_id(){
    $stmt = $this->pdo->prepare(self::$id);
    if($stmt->execute())
      throw new e_model($alert);
    if($stmt->rowCount() !== 1)
        throw new e_model('Проблема при опредении следующего user_id.');
    $user_id = (int) $stmt->fetch()['max_user_id'] + 1;
    return $user_id;
  }

  public function get_users(){
    $stmt = $this->pdo->prepare(self::$get_users);
    if($stmt->execute())
      throw new e_model($alert);
    $users = [];
    while($row = $stmt->fetch())
      $users[] = $this->create_object($row);
    return $users;
  }

  public function insert(data_user $user){
    $stmt = $this->pdo->prepare("SELECT `id` FROM `users` WHERE `username` = :login");
    $stmt->bindValue(':login', $user->get_login(), PDO::PARAM_STR);
    if($stmt->execute())
      throw new e_model($alert);
    if($stmt->rowCount() !== 0)
        throw new e_model('Пользователь с таким логином уже существует.');
    $user->set_id($this->get_insert_id());
    $user->verify('firstname', 'lastname', 'middlename', 'login', 'status');
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':user_id', $user->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', 2, PDO::PARAM_INT);
    $stmt->bindValue(':status', $user->get_status(), PDO::PARAM_STR);
    $stmt->bindValue(':login', $user->get_login(), PDO::PARAM_STR);
    $stmt->bindValue(':firstname', $user->get_firstname(), PDO::PARAM_STR);
    $stmt->bindValue(':lastname', $user->get_lastname(), PDO::PARAM_STR);
    $stmt->bindValue(':middlename', $user->get_middlename(), PDO::PARAM_STR);
    $stmt->bindValue(':password', $user->get_hash(), PDO::PARAM_STR);
    $stmt->bindValue(':telephone', $user->get_telephone(), PDO::PARAM_STR);
    $stmt->bindValue(':cellphone', $user->get_cellphone(), PDO::PARAM_STR);
    if($stmt->execute())
      throw new e_model($alert);
    return $user;
  }

  public function update(data_user $user){
    // $user->verify('id', 'firstname', 'middlename', 'lastname', 'status',
    //   'login', 'telephone');
    $stmt = $this->pdo->prepare(self::$update);
    $stmt->bindValue(':firstname', $user->get_firstname(), PDO::PARAM_STR);
    $stmt->bindValue(':lastname', $user->get_lastname(), PDO::PARAM_STR);
    $stmt->bindValue(':middlename', $user->get_middlename(), PDO::PARAM_STR);
    $stmt->bindValue(':status', $user->get_status(), PDO::PARAM_STR);
    $stmt->bindValue(':login', $user->get_login(), PDO::PARAM_STR);
    $stmt->bindValue(':telephone', $user->get_telephone(), PDO::PARAM_STR);
    $stmt->bindValue(':cellphone', $user->get_cellphone(), PDO::PARAM_STR);
    $stmt->bindValue(':password', $user->get_hash(), PDO::PARAM_STR);
    $stmt->bindValue(':id', $user->get_id(), PDO::PARAM_INT);
    if($stmt->execute())
      throw new e_model($alert);
  }
}