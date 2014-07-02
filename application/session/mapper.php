<?php

class mapper_session extends mapper{

  private static $find_all = 'SELECT `s`.`time` as `s_time`, `s`.`ip` as `s_ip`,
    `u`.`id` as `u_id`, `u`.`status` as `u_status`, `u`.`username` as `u_login`,
    `u`.`firstname` as `u_firstname`, `u`.`midlename` as `u_middlename`,
    `u`.`lastname` as `u_lastname`
    FROM `sessions_logs` as `s`, `users` as `u` WHERE `s`.`user_id` = `u`.`id`
    ORDER BY `s`.`time` DESC';

  private static $insert = 'INSERT INTO `sessions_logs` (`user_id`, `time`, `ip`
    ) VALUES (:user_id, :time, :ip)';

  private static $truncate = "TRUNCATE TABLE sessions_logs";

  public function find_all(){
    $stmt = $this->pdo->prepare(self::$find_all);
    if(!$stmt->execute())
      throw new RuntimeException();
    $sessions = [];
    $factory_session = di::get('factory_session');
    $factory_user = di::get('factory_user');
    while($row = $stmt->fetch()){
      $u_array = ['id' => $row['u_id'], 'status' => $row['u_status'],
        'login' => $row['u_login'], 'firstname' => $row['u_firstname'],
        'middlename' => $row['u_middlename'], 'lastname' => $row['u_lastname']];
      $user = $factory_user->build($u_array);
      $s_array = ['user' => $user, 'time' => $row['s_time'],
        'ip' => $row['s_ip']];
      $sessions[] = $factory_session->build($s_array);
    }
    return $sessions;
  }

  public function insert(data_session $session){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':user_id', $session->get_user()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':time', $session->get_time(), PDO::PARAM_INT);
    $stmt->bindValue(':ip', $session->get_ip(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
  }

  public function truncate(){
    $stmt = $this->pdo->prepare(self::$truncate);
    if(!$stmt->execute())
      throw new RuntimeException();
  }
}