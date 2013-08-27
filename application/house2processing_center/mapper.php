<?php
class mapper_house2processing_center{

  private $company;
  private $house;
  private $centers;

  public function __construct(data_company $company, data_house $house){
      $this->company = $company;
      $this->house = $house;
      $this->company->verify('id');
      $this->house->verify('id');
  }

  public function init_processing_centers(){
    $this->centers = $this->get_processing_centers();
  }

  private function get_processing_centers(){
    $sql = new sql();
        $sql->query("SELECT `query2number`.* , `numbers`.`number`,
            `numbers`.`fio` FROM `query2number`, `numbers`
            WHERE `query2number`.`company_id` = :company_id
            AND `numbers`.`company_id` = :company_id
            AND `query2number`.`query_id` = :query_id
            AND `query2number`.`number_id` = `numbers`.`id`");
        $sql->bind(':query_id', $this->query_id, PDO::PARAM_INT);
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->execute('Проблема при запросе связи заявка-лицевой_счет.');
        exit();
        $stmt = $sql->get_stm();
        $numbers = [];
        while($row = $stmt->fetch()){
            $number = new data_number();
            $number->id = $row['number_id'];
            $number->number = $row['number'];
            $number->fio = $row['fio'];
            $numbers[$number->id] = $number;
        }
        $stmt->closeCursor();
        return  $numbers;
  }

  public function update(){
    exit();
  }
}