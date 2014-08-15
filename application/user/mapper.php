  <?php
  class mapper_user extends mapper{

  private static $LOGIN = "SELECT id, company_id, status,
    username as login, firstname, lastname,
    midlename as middlename, password, telephone, cellphone
    FROM users WHERE username = :login AND password = :hash";

  private static $find = "SELECT id, company_id, status,
    username as login, firstname, lastname, midlename as middlename,
    password, telephone, cellphone FROM users WHERE id = :id";

  private static $find_by_login = "SELECT id, company_id, status,
    username as login, firstname, lastname, midlename as middlename,
    password, telephone, cellphone FROM users WHERE username = :login";

  private static $id = "SELECT MAX(id) as max_user_id FROM users";

  private static $update = "UPDATE users SET firstname = :firstname,
    lastname = :lastname, midlename = :middlename, status = :status,
    password = :password, telephone = :telephone, cellphone = :cellphone,
    username = :login WHERE id = :id";

  private static $insert = "INSERT INTO users (id, company_id, status,
    username, firstname, lastname, midlename, password, telephone,
    cellphone) VALUES (:user_id, :company_id, :status, :login, :firstname,
    :lastname, :middlename, :password, :telephone, :cellphone)";

  private static $find_all = "SELECT id, company_id, status,
    username as login, firstname, lastname, midlename as middlename,
    password, telephone, cellphone FROM users ORDER BY lastname";

  public function create_object(array $row){
    return di::get('factory_user')->build($row);
  }

  public function find($id){
    $stmt = $this->pdo->prepare(self::$find);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    if($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new RuntimeException();
  }

  public function find_by_login_and_password($login, $hash){
    $stmt = $this->pdo->prepare(self::$LOGIN);
    $stmt->bindValue(':login', $login, PDO::PARAM_STR);
    $stmt->bindValue(':hash', $hash, PDO::PARAM_STR);
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

  public function get_insert_id(){
    $stmt = $this->pdo->prepare(self::$id);
    if(!$stmt->execute())
      throw new RuntimeException();
    if($stmt->rowCount() !== 1)
        throw new RuntimeException();
    $user_id = (int) $stmt->fetch()['max_user_id'] + 1;
    return $user_id;
  }

  public function get_users(){
    $stmt = $this->pdo->prepare(self::$find_all);
    if(!$stmt->execute())
      throw new RuntimeException();
    $users = [];
    while($row = $stmt->fetch())
      $users[] = $this->create_object($row);
    return $users;
  }

  public function find_by_login($login){
    $stmt = $this->pdo->prepare(self::$find_by_login);
    $stmt->bindValue(':login', $login, PDO::PARAM_STR);
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

  public function insert(data_user $user){
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
    if(!$stmt->execute())
      throw new RuntimeException();
    return $user;
  }

  public function update(data_user $user){
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
    if(!$stmt->execute())
      throw new RuntimeException();
  }
}