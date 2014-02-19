<?php
class mapper_number extends mapper{

  private $company;

  private static $find = "SELECT `numbers`.`id`, `numbers`.`company_id`, 
    `numbers`.`city_id`, `numbers`.`house_id`, 
    `numbers`.`flat_id`, `numbers`.`number`,
    `numbers`.`type`, `numbers`.`status`,
    `numbers`.`fio`, `numbers`.`email`, `numbers`.`telephone`,
    `numbers`.`cellphone`, `numbers`.`password`,
    `numbers`.`contact-fio` as `contact_fio`,
    `numbers`.`contact-telephone` as `contact_telephone`,
    `numbers`.`contact-cellphone` as `contact_cellphone`,
    `flats`.`flatnumber` as `flat_number`,
    `houses`.`housenumber` as `house_number`,
    `houses`.`department_id`,
    `streets`.`name` as `street_name`
    FROM `numbers`, `flats`, `houses`, `streets`
    WHERE `numbers`.`company_id` = :company_id
    AND `numbers`.`id` = :number_id
    AND `numbers`.`flat_id` = `flats`.`id`
    AND `numbers`.`house_id` = `houses`.`id`
    AND `houses`.`street_id` = `streets`.`id`";

  private static $find_by_number = "SELECT `numbers`.`id`, `numbers`.`company_id`, 
    `numbers`.`city_id`, `numbers`.`house_id`, 
    `numbers`.`flat_id`, `numbers`.`number`,
    `numbers`.`type`, `numbers`.`status`,
    `numbers`.`fio`, `numbers`.`email`, `numbers`.`telephone`,
    `numbers`.`cellphone`, `numbers`.`password`,
    `numbers`.`contact-fio` as `contact_fio`,
    `numbers`.`contact-telephone` as `contact_telephone`,
    `numbers`.`contact-cellphone` as `contact_cellphone`,
    `flats`.`flatnumber` as `flat_number`,
    `houses`.`housenumber` as `house_number`,
    `houses`.`department_id`,
    `streets`.`name` as `street_name`
    FROM `numbers`, `flats`, `houses`, `streets`
    WHERE `numbers`.`company_id` = :company_id
    AND `numbers`.`number` = :number
    AND `numbers`.`flat_id` = `flats`.`id`
    AND `numbers`.`house_id` = `houses`.`id`
    AND `houses`.`street_id` = `streets`.`id`";

  private static $update = 'UPDATE `numbers` SET `number` = :number, `fio` = :fio,
    `email` = :email, `cellphone` = :cellphone, `telephone` = :telephone,
    `password` = :hash WHERE `company_id` = :company_id AND `id` = :id';

    public function __construct(PDO $pdo, data_company $company){
        parent::__construct($pdo);
        $this->company = $company;
    }

    public function create_object(array $row){
        $number = new data_number();
        $number->set_id($row['id']);
        $number->set_fio($row['fio']);
        $number->set_email($row['email']);
        $number->set_number($row['number']);
        $number->set_status($row['status']);
        $number->set_hash($row['password']);
        $number->set_telephone($row['telephone']);
        $number->set_cellphone($row['cellphone']);
        $house = new data_house();
        $house->set_id($row['house_id']);
        $house->set_number($row['house_number']);
        $flat = ['id' => $row['flat_id'], 'number' => $row['flat_number']];
        $flat = (new factory_flat)->create($flat);
        $flat->set_house($house);
        $number->set_flat($flat);
        return $number;
    }

    public function find($id){
        $stmt = $this->pdo->prepare(self::$find);
        $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
        $stmt->bindValue(':number_id', $id, PDO::PARAM_INT);
        if(!$stmt->execute())
          throw new RuntimeException();
        $count = $stmt->rowCount();
        if($count === 0)
            return null;
        elseif($count === 1)
            return $this->create_object($stmt->fetch());
        if($count !== 1)
            throw new RuntimeException();
    }

    public function find_by_number($num){
        $number = new data_number();
        $number->set_number($num);
        data_number::verify_num($number->get_number());
        $stmt = $this->pdo->prepare(self::$find_by_number);
        $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
        $stmt->bindValue(':number', (string) $num, PDO::PARAM_STR);
        if(!$stmt->execute())
          throw new RuntimeException();
        $count = $stmt->rowCount();
        if($count === 0)
            return null;
        elseif($count === 1)
            return $this->create_object($stmt->fetch());
        if($count !== 1)
            throw new RuntimeException();
    }

    public function update(data_number $number){
        $this->verify($number);
        $stmt = $this->pdo->prepare(self::$update);
        $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $number->get_id(), PDO::PARAM_INT);
        $stmt->bindValue(':number', $number->get_number(), PDO::PARAM_STR);
        $stmt->bindValue(':fio', $number->get_fio(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $number->get_email(), PDO::PARAM_STR);
        $stmt->bindValue(':telephone', $number->get_telephone(), PDO::PARAM_STR);
        $stmt->bindValue(':cellphone', $number->get_cellphone(), PDO::PARAM_STR);
        $stmt->bindValue(':hash', $number->get_hash(), PDO::PARAM_STR);
        if(!$stmt->execute())
          throw new RuntimeException();
        return $number;
    }


    private function verify(data_number $number){
        data_number::verify_id($number->get_id());
        data_number::verify_num($number->get_number());
        data_number::verify_fio($number->get_fio());
        data_number::verify_telephone($number->get_telephone());
        data_number::verify_cellphone($number->get_cellphone());
    }
}