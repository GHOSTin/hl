<?php
class mapper_query2number{

    private $company;
    private $query;

    public function __construct($company, $query){
        $this->company = $company;
        $this->query = $query;
        $this->company->verify('id');
        $this->query->verify('id');
    }

    private function delete(data_number $number){
        $number->verify('id');
        $sql = new sql();
        $sql->query("DELETE FROM `query2number` WHERE `company_id` = :company_id
            AND `query_id` = :query_id AND `number_id` = :number_id");
        $sql->bind(':query_id', (int) $this->query->id, PDO::PARAM_INT);
        $sql->bind(':company_id', (int) $this->company->id, PDO::PARAM_INT);
        $sql->bind(':number_id', (int) $number->id, PDO::PARAM_INT);
        $sql->execute('Проблема при удалении связи заявка-лицевой счет.');
        return $number;
    }

    private function insert(data_number $number){
        $number->verify('id');
        $sql = new sql();
        $sql->query("INSERT INTO `query2number` (`query_id`, `number_id`, `company_id`, `default`) 
                    VALUES (:query_id, :number_id, :company_id, :default)");
        $sql->bind(':query_id', (int) $this->query->id, PDO::PARAM_INT);
        $sql->bind(':company_id', (int) $this->company->id, PDO::PARAM_INT);
        $sql->bind(':default', 'false', PDO::PARAM_STR);
        $sql->bind(':number_id', (int) $number->id, PDO::PARAM_INT);
        $sql->execute('Проблема при добавлении связи заявка-лицевой счет.');
        return $number;
    }

    private function get_numbers(){
        $sql = new sql();
        $sql->query("SELECT `query2number`.* , `numbers`.`number`,
            `numbers`.`fio` FROM `query2number`, `numbers`
            WHERE `query2number`.`company_id` = :company_id
            AND `numbers`.`company_id` = :company_id
            AND `query2number`.`query_id` = :query_id
            AND `query2number`.`number_id` = `numbers`.`id`");
        $sql->bind(':query_id', $this->query->id, PDO::PARAM_INT);
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->execute('Проблема при запросе связи заявка-лицевой_счет.');
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

    public function init_numbers(){
        $sql = new sql();
        $sql->query("SELECT `query2number`.* , `numbers`.`number`,
            `numbers`.`fio` FROM `query2number`, `numbers`
            WHERE `query2number`.`company_id` = :company_id
            AND `numbers`.`company_id` = :company_id
            AND `query2number`.`query_id` = :query_id
            AND `query2number`.`number_id` = `numbers`.`id`");
        $sql->bind(':query_id', $this->query->id, PDO::PARAM_INT);
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->execute('Проблема при запросе связи заявка-лицевой_счет.');
        $stmt = $sql->get_stm();
        while($row = $stmt->fetch()){
            $number = new data_number();
            $number->id = $row['number_id'];
            $number->number = $row['number'];
            $number->fio = $row['fio'];
            $this->query->add_number($number);
        }
        $stmt->closeCursor();
        return $this->query;
    }

    public function update(){
        $new = $this->query->get_numbers();
        $old = $this->get_numbers();
        $deleted = array_diff_key($old, $new);
        $inserted = array_diff_key($new, $old);
        if(!empty($inserted))
            foreach($inserted as $number)
                $this->insert($number);
        if(!empty($deleted))
            foreach($deleted as $number)
                $this->delete($number);
        return $this->query;
    }
}