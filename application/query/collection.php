<?php
class collection_query{

  private $company;
  private $pdo;
  private $queries = [];
  private $id = [];
  private $pointer = 0;
  private $numbers = [];
  private $users = [];
  private $works = [];
  private $q2n = [];
  private $query2user = [];
  private $query2work = [];

  public function __construct(data_company $company, array $queries){
    $this->company = $company;
    data_company::verify_id($this->company->get_id());
    $this->queries = $queries;
    $this->pdo = di::get('pdo');
    if(!empty($this->queries))
      foreach($this->queries as $query)
        $this->id[] = $query->get_id();
  }

  public function count(){
    return count($this->id);
  }

  public function get_queries(){
    foreach($this->queries as $query){
      if(!empty($this->q2n[$query->get_id()]))
        foreach($this->q2n[$query->get_id()] as $number_id)
          $query->add_number($this->numbers[$number_id]);

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
      if(!empty($this->query2work[$query->get_id()]))
        foreach($this->query2work[$query->get_id()] as $work_id)
          $query->add_work($this->works[$work_id]);
      yield $query;
    }
  }

  public function create_number(array $row){
    $f_array = ['id' => $row['flat_id'], 'number' => $row['flat_number']];
    $number = new data_number();
    $number->set_id($row['number_id']);
    $number->set_number($row['number']);
    $number->set_fio($row['fio']);
    $number->set_flat(di::get('factory_flat')->build($f_array));
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

  public function create_work(array $row){
    $factory = new factory_work();
    $q2w = new data_query2work($factory->create($row));
    $q2w->set_time_open($row['time_open']);
    $q2w->set_time_close($row['time_close']);
    $q2w->set_value($row['value']);
    return $q2w;
  }

  public function init_numbers(){
    if(!empty($this->id)){
      $ids = implode(', ', $this->id);
      $stmt = $this->pdo->prepare("SELECT DISTINCT `query2number`.* , `numbers`.`number`,
          `numbers`.`fio`, `flats`.`flatnumber` as `flat_number`, `flats`.`id` as `flat_id`
          FROM `query2number`, `numbers`, `flats`
          WHERE `query2number`.`company_id` = :company_id
          AND `numbers`.`company_id` = :company_id
          AND `query2number`.`query_id` IN(".$ids.")
          AND `query2number`.`number_id` = `numbers`.`id`
          AND `numbers`.`flat_id` = `flats`.`id`");
      $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
      if(!$stmt->execute())
        throw new RuntimeException();
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
      $stmt = $this->pdo->prepare("SELECT `query2user`.`query_id`,
      `query2user`.`class`, `users`.`id`,
      `users`.`firstname`, `users`.`lastname`, `users`.`midlename`
      FROM `query2user`, `users`
      WHERE `query2user`.`company_id` = :company_id
      AND `users`.`id` = `query2user`.`user_id`
      AND `query2user`.`query_id` IN(".$ids.")");
      $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
      if(!$stmt->execute())
        throw new RuntimeException();
      while($row = $stmt->fetch()){
        $user = $this->create_user($row);
        $this->users[$user->get_id()] = $user;
        $this->query2user[$row['query_id']][$row['class']][] = $user->get_id();
      }
    }
  }

  public function init_works(){
    if(!empty($this->id)){
      $ids = implode(', ', $this->id);
      $stmt = $this->pdo->prepare("SELECT `query2work`.`query_id`, `query2work`.`opentime` as `time_open`,
        `query2work`.`closetime` as `time_close`, `query2work`.`value`,
        `works`.`id`, `works`.`name`
        FROM `query2work`, `works`
        WHERE `query2work`.`company_id` = :company_id
        AND `works`.`company_id` = :company_id
        AND `works`.`id` = `query2work`.`work_id`
        AND `query2work`.`query_id` IN(".$ids.")");
      $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
      if(!$stmt->execute())
        throw new RuntimeException();
      while($row = $stmt->fetch()){
        $work = $this->create_work($row);
        $this->works[$work->get_id()] = $work;
        $this->query2work[$row['query_id']][] = $work->get_id();
      }
    }
  }
}