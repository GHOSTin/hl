<?php
class collection_query implements iterator{

  private $company;
  private $queries = [];
  private $id = [];
  private $pointer = 0;
  private $numbers = [];
  private $q2n = [];

  public function __construct(data_company $company, array $queries){
    $this->company = $company;
    $this->company->verify('id');
    $this->queries = $queries;
    if(!empty($this->queries))
      foreach($this->queries as $query)
        $this->id[] = $query->get_id();
  }

  private function get_query($key){
    $query = $this->queries[$key];
    if(!($query instanceof data_query))
      return null;
    if(!empty($this->q2n[$query->get_id()]))
      foreach($this->q2n[$query->get_id()] as $number)
        $query->add_number($this->numbers[$number]);
    return $query;
  }

  public function create_number(array $row){
    $number = new data_number();
    $number->set_id($row['number_id']);
    $number->set_number($row['number']);
    $number->set_fio($row['fio']);
    $number->set_flat_number($row['flat_number']);
    return $number;
  }

  public function init_numbers(){
    if(!empty($this->id)){
      $ids = implode(', ', $this->id);
      $sql = new sql();
      $sql->query("SELECT `query2number`.* , `numbers`.`number`,
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

  public function rewind(){
    $this->pointer = 0;
  }

  public function current(){
    return $this->get_query($this->pointer);
  }

  public function key(){
    return $this->pointer;
  }

  public function next(){
    $row = $this->get_query($this->pointer);
    if($row instanceof data_query)
      $this->pointer++;
    return $row;
  }

  public function valid(){
    return (!is_null($this->current()));
  }
}