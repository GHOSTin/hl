<?php
class mapper_query extends mapper{

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
    `houses`.`status` as `house_status`,
    `streets`.`name` as `street_name`,
    `streets`.`id` as `street_id`,
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
    `houses`.`status` as `house_status`,
    `streets`.`name` as `street_name`,
    `streets`.`id` as `street_id`,
    `query_worktypes`.`name` as `work_type_name`,
    `departments`.`name` as `department_name`
    FROM `queries`, `houses`, `streets`, `query_worktypes`, `departments`
    WHERE `queries`.`company_id` = :company_id
    AND `queries`.`house_id` = `houses`.`id`
    AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
    AND `houses`.`street_id` = `streets`.`id`
    AND `queries`.`id` = :id AND `departments`.`company_id` = :company_id
    AND `queries`.`department_id` = `departments`.`id`";

  private static $find_all = "SELECT `queries`.`id`, `queries`.`company_id`,
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
    `houses`.`status` as `house_status`,
    `streets`.`name` as `street_name`,
    `streets`.`id` as `street_id`,
    `query_worktypes`.`name` as `work_type_name`,
    `departments`.`name` as `department_name`
    FROM `queries`, `houses`, `streets`, `query_worktypes`, `departments`
    WHERE `queries`.`company_id` = :company_id
    AND `queries`.`house_id` = `houses`.`id`
    AND `houses`.`street_id` = `streets`.`id`
    AND `queries`.`query_worktype_id` = `query_worktypes`.`id`
    AND `departments`.`company_id` = :company_id
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
    `houses`.`status` as `house_status`,
    `streets`.`name` as `street_name`,
    `streets`.`id` as `street_id`,
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

  public function __construct(PDO $pdo, data_company $company){
    parent::__construct($pdo);
    $this->company = $company;
  }

  public function create_object(array $row){
    $wt = new data_query_work_type();
    $wt->set_id($row['worktype_id']);
    $wt->set_name($row['work_type_name']);
    $d_array = ['id' =>$row['department_id'],
      'name' => $row['department_name']];
    $s_array = ['id' => $row['street_id'], 'name' => $row['street_name']];
    $h_array = ['id' => $row['house_id'], 'number' => $row['house_number'],
      'status' => $row['house_status'],
      'street' => di::get('factory_street')->build($s_array)];
    $q_array = ['id' => $row['id'], 'status' => $row['status'],
      'initiator' => $row['initiator'],
      'payment_status' => $row['payment_status'],
      'warning_status' => $row['warning_status'],
      'close_reason' => $row['close_reason'],
      'time_open' => $row['time_open'],
      'time_work' => $row['time_work'],
      'time_close' => $row['time_close'],
      'description' => $row['description'],
      'number' => $row['number'],
      'contact_fio' => $row['contact_fio'],
      'contact_telephone' => $row['contact_telephone'],
      'contact_cellphone' => $row['contact_cellphone'],
      'department' => di::get('factory_department')->build($d_array),
      'house' => di::get('factory_house')->build($h_array),
      'type' => $wt];
    return di::get('factory_query')->build($q_array);
  }

  public function insert(data_query $query){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':id', $query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':status', $query->get_status(), PDO::PARAM_STR);
    $stmt->bindValue(':initiator', $query->get_initiator(), PDO::PARAM_STR);
    $stmt->bindValue(':payment_status', $query->get_payment_status(), PDO::PARAM_STR);
    $stmt->bindValue(':warning_status', $query->get_warning_status(), PDO::PARAM_STR);
    $stmt->bindValue(':department_id', $query->get_department()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':house_id', $query->get_house()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':worktype_id', $query->get_work_type()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':time_open', $query->get_time_open(), PDO::PARAM_INT);
    $stmt->bindValue(':time_work', $query->get_time_work(), PDO::PARAM_INT);
    $stmt->bindValue(':contact_fio', $query->get_contact_fio(), PDO::PARAM_STR);
    $stmt->bindValue(':contact_telephone', $query->get_contact_telephone(), PDO::PARAM_STR);
    $stmt->bindValue(':contact_cellphone', $query->get_contact_cellphone(), PDO::PARAM_STR);
    $stmt->bindValue(':description', $query->get_description(), PDO::PARAM_STR);
    $stmt->bindValue(':number', $query->get_number(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $query;
  }

  public function find($query_id){
    $stmt = $this->pdo->prepare(self::$find);
    $stmt->bindValue(':id', (int) $query_id, PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
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

  public function find_all(){
    $query = self::$find_all;
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
        throw new RuntimeException();
    $queries = [];
    while($row = $stmt->fetch())
      $queries[] = $this->create_object($row);
    return $queries;
  }

  public function get_queries_by_number($number){
    $stmt = $this->pdo->prepare(self::$find_by_number);
    $stmt->bindValue(':number', (int) $number, PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $queries = [];
    while($row = $stmt->fetch())
      $queries[] = $this->create_object($row);
    return $queries;
  }

  public function update(data_query $query){
    $stmt = $this->pdo->prepare(self::$update);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':id', $query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':payment_status', $query->get_payment_status(), PDO::PARAM_STR);
    $stmt->bindValue(':warning_status', $query->get_warning_status(), PDO::PARAM_STR);
    $stmt->bindValue(':fio', $query->get_contact_fio(), PDO::PARAM_STR);
    $stmt->bindValue(':telephone', $query->get_contact_telephone(), PDO::PARAM_STR);
    $stmt->bindValue(':cellphone', $query->get_contact_cellphone(), PDO::PARAM_STR);
    $stmt->bindValue(':close_reason', $query->get_close_reason(), PDO::PARAM_STR);
    $stmt->bindValue(':description', $query->get_description(), PDO::PARAM_STR);
    $stmt->bindValue(':work_type_id', $query->get_work_type()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':status', $query->get_status(), PDO::PARAM_STR);
    $stmt->bindValue(':time_work', $query->get_time_work(), PDO::PARAM_INT);
    $stmt->bindValue(':time_close', $query->get_time_close(), PDO::PARAM_INT);
    $stmt->bindValue(':house_id', $query->get_house()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':initiator', $query->get_initiator(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $query;
  }

  public function get_insert_id(){
    $stmt = $this->pdo->prepare(self::$id);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
        throw new RuntimeException();
    if($stmt->rowCount() !== 1)
      throw new RuntimeException();
    return (int) $stmt->fetch()['max_query_id'] + 1;
  }

  public function get_insert_query_number($time){
    $time = getdate($time);
    $stmt = $this->pdo->prepare(self::$number);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':begin', mktime(0, 0, 0, 1, 1, $time['year']), PDO::PARAM_INT);
    $stmt->bindValue(':end', mktime(23, 59, 59, 12, 31, $time['year']), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    if($stmt->rowCount() !== 1)
      throw new RuntimeException();
    return (int) $stmt->fetch()['querynumber'] + 1;
  }

  public function get_queries(array $params){
    $query = self::$many;
    $prms = [];
    if(!empty($params['status'])){
      $query .= " AND `queries`.`status` = :status";
      $prms[] = [':status', (string) $params['status'], PDO::PARAM_STR];
    }
    if(!empty($params['department'])){
      foreach($params['department']as $key => $department){
       $department_key[] = ':department_id'.$key;
       $prms[] = [':department_id'.$key, (int) $department, PDO::PARAM_INT];
      }
      $query .= " AND `queries`.`department_id` IN(".implode(',', $department_key).")";
    }
    if(!empty($params['street'])){
      $query .= " AND `houses`.`street_id` = :street_id";
      $prms[] = [':street_id', (int) $params['street'], PDO::PARAM_INT];
    }
    if(!empty($params['house'])){
      $query .= " AND `queries`.`house_id` = :house_id";
      $prms[] = [':house_id', (int) $params['house'], PDO::PARAM_INT];
    }
    if(!empty($params['work_type'])){
      $query .= " AND `queries`.`query_worktype_id` = :worktype_id";
      $prms[] = [':worktype_id', (int) $params['work_type'], PDO::PARAM_INT];
    }
    $query .= " ORDER BY `queries`.`opentime` DESC";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':time_open', $params['time_open_begin'], PDO::PARAM_INT);
    $stmt->bindValue(':time_close', $params['time_open_end'], PDO::PARAM_INT);
    if(!empty($prms))
      foreach($prms as $param)
        $stmt->bindValue($param[0], $param[1], $param[2]);
    if(!$stmt->execute())
        throw new RuntimeException();
    $queries = [];
    while($row = $stmt->fetch())
      $queries[] = $this->create_object($row);
    return $queries;
  }


  public function get_queries_by_house(data_house $house){
    $query = "SELECT `q`.`id`, `q`.`company_id`,
    `q`.`status`, `q`.`initiator-type` as `initiator`,
    `q`.`payment-status` as `payment_status`,
    `q`.`warning-type` as `warning_status`,
    `q`.`department_id`, `q`.`house_id`,
    `q`.`query_close_reason_id` as `close_reason_id`,
    `q`.`query_worktype_id` as `worktype_id`,
    `q`.`opentime` as `time_open`,
    `q`.`worktime` as `time_work`,
    `q`.`closetime` as `time_close`,
    `q`.`addinfo-name` as `contact_fio`,
    `q`.`addinfo-telephone` as `contact_telephone`,
    `q`.`addinfo-cellphone` as `contact_cellphone`,
    `q`.`description-open` as `description`,
    `q`.`description-close` as `close_reason`,
    `q`.`querynumber` as `number`,
    `q`.`query_inspection` as `inspection`,
    `houses`.`housenumber` as `house_number`,
    `houses`.`status` as `house_status`,
    `streets`.`name` as `street_name`,
    `streets`.`id` as `street_id`,
    `query_worktypes`.`name` as `work_type_name`,
    `departments`.`name` as `department_name`
    FROM `queries` as `q`, `houses`, `streets`, `query_worktypes`, `departments`
    WHERE `q`.`company_id` = :company_id
    AND `q`.`house_id` = `houses`.`id`
    AND `houses`.`street_id` = `streets`.`id`
    AND `q`.`query_worktype_id` = `query_worktypes`.`id`
    AND `opentime` > :time_open AND `departments`.`company_id` = :company_id
    AND `q`.`department_id` = `departments`.`id` AND `q`.`house_id` = :house_id
    ORDER BY `q`.`opentime` DESC LIMIT 5";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':time_open', time() - 86400 * 30, PDO::PARAM_INT);
    $stmt->bindValue(':house_id', $house->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
        throw new RuntimeException();
    $queries = [];
    while($row = $stmt->fetch())
      $queries[] = $this->create_object($row);
    return $queries;
  }
}