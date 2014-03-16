<?php

class mapper_session extends mapper{

  private static $insert = 'INSERT INTO `sessions_logs` (`user_id`, `time`, `ip`
    ) VALUES (:user_id, :time, :ip)';

  public function insert(data_session $session){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':user_id', $session->get_user()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':time', $session->get_time(), PDO::PARAM_INT);
    $stmt->bindValue(':ip', $session->get_ip(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
  }
}