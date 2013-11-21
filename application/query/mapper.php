<?php
class mapper_query{

  private $company;

  private static $find_by_number = "SELECT `queries`.`id`,
    `queries`.`status`, `queries`.`initiator-type` as `initiator`,
    `queries`.`payment-status` as `payment_status`,
    `queries`.`warning-type` as `warning_status`,
    `queries`.`department_id`, `queries`.`house_id`,
    `queries`.`query_close_reason_id` as `close_reason_id`,
    `queries`.`query_worktype_id` as `worktype_id`,
    `queries`.`opentime` as `time_open`,
    `queries`.`worktime` as `time_work`,
    `queries`.`closetime` as `time_close`,
    `queries`.`addinfo-name` as `contact_fio`,
    `queries`.`addinfo-telephone` as `contact_telephone`,
    `queries`.`addinfo-cellphone` as `contact_cellphone`,
    `queries`.`description-open` as `description`,
    `queries`.`description-close` as `close_reason`,
    `queries`.`querynumber` as `number`,
    `queries`.`query_inspection` as `inspection`, 
    `houses`.`housenumber` as `house_number`,
    `streets`.`name` as `street_name`,
    `query_worktypes`.`name` as `work_type_name`,
    `departments`.`name` as `department_name`
    FROM `queries`, `houses`, `streets`, `query_worktypes` , `departments`
    WHERE `queries`.`company_id` = :company_id
    AND `queries`.`house_id` = `houses`.`id`
    AND `houses`.`street_id` = `streets`.`id`
    AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
    AND `querynumber` = :number AND `departments`.`company_id` = :company_id
    AND `queries`.`department_id` = `departments`.`id`
    ORDER BY `opentime` DESC";

  private static $find = "SELECT `queries`.`id`, `queries`.`company_id`,
    `queries`.`status`, `queries`.`initiator-type` as `initiator`,
    `queries`.`payment-status` as `payment_status`,
    `queries`.`warning-type` as `warning_status`,
    `queries`.`department_id`, `queries`.`house_id`,
    `queries`.`query_close_reason_id` as `close_reason_id`,
    `queries`.`query_worktype_id` as `worktype_id`,
    `queries`.`opentime` as `time_open`,
    `queries`.`worktime` as `time_work`,
    `queries`.`closetime` as `time_close`,
    `queries`.`addinfo-name` as `contact_fio`,
    `queries`.`addinfo-telephone` as `contact_telephone`,
    `queries`.`addinfo-cellphone` as `contact_cellphone`,
    `queries`.`description-open` as `description`,
    `queries`.`description-close` as `close_reason`,
    `queries`.`querynumber` as `number`,
    `queries`.`query_inspection` as `inspection`, 
    `houses`.`housenumber` as `house_number`,
    `streets`.`name` as `street_name`,
    `query_worktypes`.`name` as `work_type_name`,
    `departments`.`name` as `department_name`
    FROM `queries`, `houses`, `streets`, `query_worktypes`, `departments`
    WHERE `queries`.`company_id` = :company_id
    AND `queries`.`house_id` = `houses`.`id`
    AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
    AND `houses`.`street_id` = `streets`.`id`
    AND `queries`.`id` = :id AND `departments`.`company_id` = :company_id
    AND `queries`.`department_id` = `departments`.`id`";

  private static $insert =  "INSERT INTO `queries` (
    `id`, `company_id`, `status`, `initiator-type`, `payment-status`,
    `warning-type`, `department_id`, `house_id`, `query_worktype_id`,
    `opentime`, `worktime`, `addinfo-name`, `addinfo-telephone`,
    `addinfo-cellphone`, `description-open`, `querynumber`)
    VALUES (:id, :company_id, :status, :initiator, :payment_status, 
    :warning_status,:department_id, :house_id, :worktype_id, :time_open,
    :time_work, :contact_fio, :contact_telephone, :contact_cellphone,
    :description, :number)";

  private static $update = "UPDATE `queries`
    SET `payment-status` = :payment_status, `warning-type` = :warning_status,
    `addinfo-name` = :fio, `addinfo-telephone` = :telephone,
    `addinfo-cellphone` = :cellphone, `description-close` = :close_reason,
    `description-open` = :description, `query_worktype_id` = :work_type_id,
    `status` = :status, `worktime` = :time_work, `closetime` = :time_close,
    `house_id` = :house_id, `initiator-type` = :initiator
    WHERE `company_id` = :company_id AND `id` = :id";

  private static $id = "SELECT MAX(`id`) as `max_query_id` FROM `queries`
    WHERE `company_id` = :company_id";

  private static $number = "SELECT MAX(`querynumber`) as `querynumber`
    FROM `queries` WHERE `opentime` > :begin AND `opentime` <= :end
    AND `company_id` = :company_id";

  private static $many = "SELECT `queries`.`id`, `queries`.`company_id`,
    `queries`.`status`, `queries`.`initiator-type` as `initiator`,
    `queries`.`payment-status` as `payment_status`,
    `queries`.`warning-type` as `warning_status`,
    `queries`.`department_id`, `queries`.`house_id`,
    `queries`.`query_close_reason_id` as `close_reason_id`,
    `queries`.`query_worktype_id` as `worktype_id`,
    `queries`.`opentime` as `time_open`,
    `queries`.`worktime` as `time_work`,
    `queries`.`closetime` as `time_close`,
    `queries`.`addinfo-name` as `contact_fio`,
    `queries`.`addinfo-telephone` as `contact_telephone`,
    `queries`.`addinfo-cellphone` as `contact_cellphone`,
    `queries`.`description-open` as `description`,
    `queries`.`description-close` as `close_reason`,
    `queries`.`querynumber` as `number`,
    `queries`.`query_inspection` as `inspection`, 
    `houses`.`housenumber` as `house_number`,
    `streets`.`name` as `street_name`,
    `query_worktypes`.`name` as `work_type_name`,
    `departments`.`name` as `department_name`
    FROM `queries`, `houses`, `streets`, `query_worktypes`, `departments`
    WHERE `queries`.`company_id` = :company_id
    AND `queries`.`house_id` = `houses`.`id`
    AND `houses`.`street_id` = `streets`.`id`
    AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
    AND `opentime` > :time_open
    AND `opentime` <= :time_close AND `departments`.`company_id` = :company_id
    AND `queries`.`department_id` = `departments`.`id`";

  public function __construct(data_company $company){
    $this->company = $company;
    data_company::verify_id($this->company->get_id());
  }

  public function create_object(array $row){
    $query = new data_query();
    $query->set_id($row['id']);
    $query->set_status($row['status']);
    $query->set_initiator($row['initiator']);
    $query->set_payment_status($row['payment_status']);
    $query->set_warning_status($row['warning_status']);
    $query->set_close_reason($row['close_reason']);
    $query->set_time_open($row['time_open']);
    $query->set_time_work($row['time_work']);
    $query->set_time_close($row['time_close']);
    $query->set_description($row['description']);
    $query->set_number($row['number']);
    $query->set_contact_fio($row['contact_fio']);
    $query->set_contact_telephone($row['contact_telephone']);
    $query->set_contact_cellphone($row['contact_cellphone']);

    $department = new data_department();
    $department->set_id($row['department_id']);
    $department->set_name($row['department_name']);
    $query->set_department($department);

    $house = new data_house();
    $house->set_id($row['house_id']);
    $house->set_number($row['house_number']);
    $query->set_house($house);

    $street = new data_street();
    $street->set_name($row['street_name']);
    $query->set_street($street);

    $wt = new data_query_work_type();
    $wt->set_id($row['worktype_id']);
    $wt->set_name($row['work_type_name']);
    $query->add_work_type($wt);

    return $query;
  }

  public function insert(data_query $query){
    $this->verify($query);
    data_house::verify_id($query->get_house()->get_id());
    data_department::verify_id($query->get_department()->get_id());
    data_query_work_type::verify_id($query->get_work_type()->get_id());
    $sql = new sql();
    $sql->query(self::$insert);
    $sql->bind(':id', $query->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT, 3);
    $sql->bind(':status', $query->get_status(), PDO::PARAM_STR);
    $sql->bind(':initiator', $query->get_initiator(), PDO::PARAM_STR);
    $sql->bind(':payment_status', $query->get_payment_status(), PDO::PARAM_STR);
    $sql->bind(':warning_status', $query->get_warning_status(), PDO::PARAM_STR);
    $sql->bind(':department_id', $query->get_department()->get_id(), PDO::PARAM_INT);
    $sql->bind(':house_id', $query->get_house()->get_id(), PDO::PARAM_INT);
    $sql->bind(':worktype_id', $query->get_work_type()->get_id(), PDO::PARAM_INT);
    $sql->bind(':time_open', $query->get_time_open(), PDO::PARAM_INT);
    $sql->bind(':time_work', $query->get_time_work(), PDO::PARAM_INT);
    $sql->bind(':contact_fio', $query->get_contact_fio(), PDO::PARAM_STR);
    $sql->bind(':contact_telephone', $query->get_contact_telephone(), PDO::PARAM_STR);
    $sql->bind(':contact_cellphone', $query->get_contact_cellphone(), PDO::PARAM_STR);
    $sql->bind(':description', $query->get_description(), PDO::PARAM_STR);
    $sql->bind(':number', $query->get_number(), PDO::PARAM_INT);
    $sql->execute('Проблемы при создании заявки.');
    return $query;
  }

  public function find($query_id){
    $sql = new sql();
    $sql->query(self::$find);
    $sql->bind(':id', (int) $query_id, PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборке заявки');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
        return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
        throw new e_model('Неожиданное количество возвращаемых заявок.');
  }

  /**
  * Возвращает заявки.
  * @return array
  */
  public function get_queries_by_number($number){
    $sql = new sql();
    $sql->query(self::$find_by_number);
    $sql->bind(':number', (int) $number, PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборки заявко по номеру.');
    $stmt = $sql->get_stm();
    $queries = [];
    while($row = $stmt->fetch())
      $queries[] = $this->create_object($row);
    return $queries;
  }

  public function update(data_query $query){
    $this->verify($query);
    data_house::verify_id($query->get_house()->get_id());
    data_query_work_type::verify_id($query->get_work_type()->get_id());
    $sql = new sql();
    $sql->query(self::$update);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':id', $query->get_id(), PDO::PARAM_INT);
    $sql->bind(':payment_status', $query->get_payment_status(), PDO::PARAM_STR);
    $sql->bind(':warning_status', $query->get_warning_status(), PDO::PARAM_STR);
    $sql->bind(':fio', $query->get_contact_fio(), PDO::PARAM_STR);
    $sql->bind(':telephone', $query->get_contact_telephone(), PDO::PARAM_STR);
    $sql->bind(':cellphone', $query->get_contact_cellphone(), PDO::PARAM_STR);
    $sql->bind(':close_reason', $query->get_close_reason(), PDO::PARAM_STR);
    $sql->bind(':description', $query->get_description(), PDO::PARAM_STR);
    $sql->bind(':work_type_id', $query->get_work_type()->get_id(), PDO::PARAM_INT);
    $sql->bind(':status', $query->get_status(), PDO::PARAM_STR);
    $sql->bind(':time_work', $query->get_time_work(), PDO::PARAM_INT);
    $sql->bind(':time_close', $query->get_time_close(), PDO::PARAM_INT);
    $sql->bind(':house_id', $query->get_house()->get_id(), PDO::PARAM_INT);
    $sql->bind(':initiator', $query->get_initiator(), PDO::PARAM_STR);
    $sql->execute('Ошибка при обновлении заявки.');
    return $query;
  }

  /*
  * Возвращает следующий для вставки идентификатор заявки.
  */
  public function get_insert_id(){
    $sql = new sql();
    $sql->query(self::$id);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при опредении следующего query_id.');
    if($sql->count() !== 1)
      throw new e_model('Проблема при опредении следующего query_id.');
    $query_id = (int) $sql->row()['max_query_id'] + 1;
    $sql->close();
    return $query_id;
  }

  /*
  * Возвращает следующий для вставки номер заявки.
  */
  public function get_insert_query_number($time){
    $time = getdate($time);
    $sql = new sql();
    $sql->query(self::$number);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':begin', mktime(0, 0, 0, 1, 1, $time['year']), PDO::PARAM_INT);
    $sql->bind(':end', mktime(23, 59, 59, 12, 31, $time['year']), PDO::PARAM_INT);
    $sql->execute('Проблема при опредении следующего querynumber.');
    if($sql->count() !== 1)
      throw new e_model('Проблема при опредении следующего querynumber.');
    $query_number = (int) $sql->row()['querynumber'] + 1;
    $sql->close();
    return $query_number;
  }

  /**
  * Возвращает заявки.
  * @return array
  */
  public function get_queries(array $params){
    $sql = new sql();
    $sql->query(self::$many);
    $sql->bind(':time_open', $params['time_open_begin'], PDO::PARAM_INT);
    $sql->bind(':time_close', $params['time_open_end'], PDO::PARAM_INT);
    if(!empty($params['status'])){
      $sql->query(" AND `queries`.`status` = :status");
      $sql->bind(':status', (string) $params['status'], PDO::PARAM_STR);
    }
    if(!empty($params['department'])){
      foreach($params['department']as $key => $department){
       $department_key[] = ':department_id'.$key;
       $sql->bind(':department_id'.$key, (int) $department, PDO::PARAM_INT);
      }
      $sql->query(" AND `queries`.`department_id` IN(".implode(',', $department_key).")");
    }
    if(!empty($params['street'])){
      $sql->query(" AND `houses`.`street_id` = :street_id");
      $sql->bind(':street_id', (int) $params['street'], PDO::PARAM_INT);
    }
    if(!empty($params['house'])){
      $sql->query(" AND `queries`.`house_id` = :house_id");
      $sql->bind(':house_id', (int) $params['house'], PDO::PARAM_INT);
    }
    if(!empty($params['work_type'])){
      $sql->query(" AND `queries`.`query_worktype_id` = :worktype_id");
      $sql->bind(':worktype_id', (int) $params['work_type'], PDO::PARAM_INT);
    }
    $sql->query(" ORDER BY `queries`.`opentime` DESC");
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборки заявко по номеру.');
    $stmt = $sql->get_stm();
    $queries = [];
    while($row = $stmt->fetch())
      $queries[] = $this->create_object($row);
    return $queries;
  }

  private function verify(data_query $query){
    data_query::verify_id($query->get_id());
    data_query::verify_status($query->get_status());
    data_query::verify_payment_status($query->get_payment_status());
    data_query::verify_warning_status($query->get_warning_status());
    data_query::verify_contact_fio($query->get_contact_fio());
    data_query::verify_contact_telephone($query->get_contact_telephone());
    data_query::verify_close_reason($query->get_close_reason());
    data_query::verify_description($query->get_description());
    data_query::verify_status($query->get_status());
    data_query::verify_time_open($query->get_time_open());
    data_query::verify_time_work($query->get_time_work());
    data_query::verify_initiator($query->get_initiator());
    data_query::verify_number($query->get_number());
  }
}