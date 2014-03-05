<?php
class mapper_house2number{

  private $company;
  private $house;
  private $pdo;

  private static $many = "SELECT `numbers`.`id`, `numbers`.`company_id`,
    `numbers`.`city_id`, `numbers`.`house_id`, `numbers`.`flat_id`,
    `numbers`.`number`, `numbers`.`type`, `numbers`.`status`, `numbers`.`fio`,
    `numbers`.`telephone`, `numbers`.`cellphone`, `numbers`.`password`,
    `numbers`.`contact-fio` as `contact_fio`,
    `numbers`.`contact-telephone` as `contact_telephone`,
    `numbers`.`contact-cellphone` as `contact_cellphone`,
    `flats`.`flatnumber` as `flat_number`,
    `houses`.`housenumber` as `house_number`,
    `streets`.`name` as `street_name`
    FROM `numbers`, `flats`, `houses`, `streets`
    WHERE `numbers`.`house_id` = :house_id
    AND `numbers`.`company_id` = :company_id
    AND `numbers`.`flat_id` = `flats`.`id`
    AND `numbers`.`house_id` = `houses`.`id`
    AND `houses`.`street_id` = `streets`.`id`
    ORDER BY (`flats`.`flatnumber` + 0)";

  private static $one_by_number = "SELECT `numbers`.`id`, `numbers`.`company_id`,
    `numbers`.`city_id`, `numbers`.`house_id`, `numbers`.`flat_id`,
    `numbers`.`number`, `numbers`.`type`, `numbers`.`status`, `numbers`.`fio`,
    `numbers`.`telephone`, `numbers`.`cellphone`, `numbers`.`password`,
    `numbers`.`contact-fio` as `contact_fio`,
    `numbers`.`contact-telephone` as `contact_telephone`,
    `numbers`.`contact-cellphone` as `contact_cellphone`,
    `flats`.`flatnumber` as `flat_number`,
    `houses`.`housenumber` as `house_number`,
    `streets`.`name` as `street_name`
    FROM `numbers`, `flats`, `houses`, `streets`
    WHERE `numbers`.`house_id` = :house_id
    AND `numbers`.`company_id` = :company_id
    AND `numbers`.`flat_id` = `flats`.`id`
    AND `numbers`.`house_id` = `houses`.`id`
    AND `houses`.`street_id` = `streets`.`id`
    AND `numbers`.`number` = :number";

  private static $insert = "INSERT INTO `numbers` (`id`, `company_id`,
    `city_id`, `house_id`, `flat_id`, `number`, `type`, `status`, `fio`,
    `telephone`, `cellphone`, `password`, `contact-fio`, `contact-telephone`,
    `contact-cellphone`) VALUES (:id, :company_id, :city_id, :house_id,
    :flat_id, :number, :type, :status, :fio, :telephone, :cellphone, :password,
    :contact_fio, :contact_telephone, :contact_cellphone)";

  private static $id = "SELECT MAX(`id`) as `max_number_id` FROM `numbers`
    WHERE `company_id` = :company_id AND `city_id` = :city_id";

  public function __construct(data_company $company, data_house $house){
    $this->company = $company;
    $this->house = $house;
    data_company::verify_id($this->company->get_id());
    data_house::verify_id($this->house->get_id());
    data_city::verify_id($this->house->get_city()->get_id());
    $this->pdo = di::get('pdo');
  }

  public function create_object(array $row){
    $number = di::get('factory_number')->build($row);
    $flat = ['id' => $row['flat_id'], 'number' => $row['flat_number']];
    $number->set_flat(di::get('factory_flat')->build($flat));
    return $number;
  }

  public function get_number_by_number($number){
    $stmt = $this->pdo->prepare(self::$one_by_number);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':house_id', $this->house->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':number', (string) $number, PDO::PARAM_STR);
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

  private function get_numbers(){
    $stmt = $this->pdo->prepare(self::$many);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':house_id', (int) $this->house->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $numbers = [];
    if($stmt->rowCount() > 0)
      while($row = $stmt->fetch())
        $numbers[] = $this->create_object($row);
    return $numbers;
  }

  public function init_numbers(){
    $numbers = $this->get_numbers();
    if(!empty($numbers))
      foreach($numbers as $number)
        $this->house->add_number($number);
  }

  public function insert(data_number $number){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':id', (int) $number->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int)$this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':city_id', (int) $this->house->get_city()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':house_id', (int) $this->house->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':flat_id', (int) $number->get_flat()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':number', (string) $number->get_number(), PDO::PARAM_STR);
    $stmt->bindValue(':type', 'human', PDO::PARAM_STR);
    $stmt->bindValue(':status', (string) $number->get_status(), PDO::PARAM_STR);
    $stmt->bindValue(':fio', (string) $number->get_fio(), PDO::PARAM_STR);
    $stmt->bindValue(':telephone', '', PDO::PARAM_STR);
    $stmt->bindValue(':cellphone', '', PDO::PARAM_STR);
    $stmt->bindValue(':password', '', PDO::PARAM_STR);
    $stmt->bindValue(':contact_fio', '', PDO::PARAM_STR);
    $stmt->bindValue(':contact_telephone', '', PDO::PARAM_STR);
    $stmt->bindValue(':contact_cellphone', '', PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $number;
  }

  public function get_insert_id(){
    $stmt = $this->pdo->prepare(self::$id);
    $stmt->bindValue(':city_id', (int) $this->house->get_city()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    if($stmt->rowCount() !== 1)
      throw new RuntimeException();
    $number_id = (int) $stmt->fetch()['max_number_id'] + 1;
    return $number_id;
  }

  private function verify(data_number $number){
    data_number::verify_id($number->get_id());
    data_number::verify_number($number->get_number());
    data_number::verify_fio($number->get_fio());
    data_number::verify_status($number->get_status());
    data_flat::verify_id($number->get_flat()->get_id());
  }
}