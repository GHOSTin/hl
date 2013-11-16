<?php
class collection_query{

  private $company;
  private $queries = [];
  private $id = [];
  private $pointer = 0;
  private $numbers = [];
  private $q2n = [];
  private $query2user = [];

  public function __construct(data_company $company, array $queries){
    $this->company = $company;
    $this->company->verify('id');
    $this->queries = $queries;
    if(!empty($this->queries))
      foreach($this->queries as $query)
        $this->id[] = $query->get_id();
  }

  public function count(){
    return count($this->id);
  }

  public function get_queries(){
    foreach($this->queries as $query){
      if(!empty($this->query2user[$query->get_id()]['creator'])){
        $user_id = $this->query2user[$query->get_id()]['creator'][0];
        $query->add_creator($this->users[$user_id]);
      }
      if(!empty($this->query2user[$query->get_id()]['manager']))
        foreach($this->query2user[$query->get_id()]['manager'] as $user_id)
          $query->add_manager($this->users[$user_id]);

      if(!empty($this->query2user[$query->get_id()]['performer']))
        foreach($this->query2user[$query->get_id()]['performer'] as $user_id)
          $query->add_performer($this->users[$user_id]);

      if(!empty($this->query2user[$query->get_id()]['observer']))
        foreach($this->query2user[$query->get_id()]['observer'] as $user_id)
          $query->add_observer($this->users[$user_id]);
      yield $query;
    }
  }

  public function create_number(array $row){
    $number = new data_number();
    $number->set_id($row['number_id']);
    $number->set_number($row['number']);
    $number->set_fio($row['fio']);
    $flat = new data_flat();
    $flat->set_number($row['flat_number']);
    $number->set_flat($flat);
    return $number;
  }

  public function create_user(array $row){
    $user = new data_user();
    $user->set_id($row['id']);
    $user->set_firstname($row['firstname']);
    $user->set_middlename($row['middlename']);
    $user->set_lastname($row['lastname']);
    return $user;
  }

  public function init_numbers(){
    if(!empty($this->id)){
      $ids = implode(', ', $this->id);
      $sql = new sql();
      $sql->query("SELECT DISTINCT `query2number`.* , `numbers`.`number`,
          `numbers`.`fio`, `flats`.`flatnumber` as `flat_number`
          FROM `query2number`, `numbers`, `flats`
          WHERE `query2number`.`company_id` = :company_id
          AND `numbers`.`company_id` = :company_id
          AND `query2number`.`query_id` IN(".$ids.")
          AND `query2number`.`number_id` = `numbers`.`id`
          AND `numbers`.`flat_id` = `flats`.`id`");
      $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
      $sql->execute('Проблемы при выборе лицевых счетов.');
      $stmt = $sql->get_stm();
      while($row = $stmt->fetch()){
        $number = $this->create_number($row);
        $this->numbers[$number->get_id()] = $number;
        $this->q2n[$row['query_id']][] = $number->get_id();
      }
    }
  }

  public function init_users(){
    if(!empty($this->id)){
      $ids = implode(', ', $this->id);
      $sql = new sql();
      $sql->query("SELECT `query2user`.`query_id`,
      `query2user`.`class`, `users`.`id`,
      `users`.`firstname`, `users`.`lastname`, `users`.`midlename`
      FROM `query2user`, `users`
      WHERE `query2user`.`company_id` = :company_id
      AND `users`.`id` = `query2user`.`user_id`
      AND `query2user`.`query_id` IN(".$ids.")");
      $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
      $sql->execute('Проблемы при выборе лицевых счетов.');
      $stmt = $sql->get_stm();
      while($row = $stmt->fetch()){
        $user = $this->create_user($row);
        $this->users[$user->get_id()] = $user;
        $this->query2user[$row['query_id']][$row['class']][] = $user->get_id();
      }
    }
  }
}