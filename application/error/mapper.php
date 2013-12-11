<?php

class mapper_error{

  private $pdo;

  private static $find_all = "SELECT `errors`.`time`, `errors`.`user_id`,
    `errors`.`text`, `users`.`id`, `users`.`firstname`, `users`.`midlename`,
    `users`.`lastname` FROM `errors`, `users`
    WHERE `errors`.`user_id` = `users`.`id`";

  private static $insert = "INSERT INTO `errors` (`time`, `user_id`, `text`)
    VALUES (:time, :user_id, :text)";

  public function __construct(PDO $pdo){
    $this->pdo = $pdo;
  }

  public function create_object(array $row){
    $user = new data_user();
    $user->set_id($row['id']);
    $user->set_firstname($row['firstname']);
    $user->set_middlename($row['midlename']);
    $user->set_lastname($row['lastname']);
    // ошибка
    $error = new data_error();
    $error->set_time($row['time']);
    $error->set_text($row['text']);
    $error->set_user($user);
    return $error;
  }

  public function find_all(){
    $stmt = $this->pdo->prepare(self::$find_all);
    if(!$stmt->execute())
      throw new e_model('Проблема при выборке сообщений об ошибке.');
    $errors = [];
    while($row = $stmt->fetch())
      $errors[] = $this->create_object($row);
    return $errors;
  }

  public function insert(data_error $error){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':time', $error->get_time(), PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $error->get_user()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':text', $error->get_text(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new e_model('Проблема при вставке сообщения об ошибке.');
    return $error;
  }
}