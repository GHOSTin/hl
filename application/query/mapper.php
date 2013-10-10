<?php
class mapper_query{

  private $company;

  private static $sql_select_by_number = "SELECT `queries`.`id`,
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

  private static $sql_find = "SELECT `queries`.`id`, `queries`.`company_id`,
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

  public function __construct(data_company $company){
      $this->company = $company;
      $this->company->verify('id');
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
    $department->set_name('department_name');
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
    $query->verify('id', 'status', 'initiator', 'payment_status',
            'warning_status', 'time_open', 'time_work', 'contact_fio',
            'contact_telephone', 'contact_cellphone', 'description', 'number');
    $query->get_house()->verify('id');
    $query->get_department()->verify('id');
    $query->get_work_type()->verify('id');
    $sql = new sql();
    $sql->query("INSERT INTO `queries` (
          `id`, `company_id`, `status`, `initiator-type`, `payment-status`,
          `warning-type`, `department_id`, `house_id`, `query_worktype_id`,
          `opentime`, `worktime`, `addinfo-name`, `addinfo-telephone`,
          `addinfo-cellphone`, `description-open`, `querynumber`)
          VALUES (:id, :company_id, :status, :initiator, :payment_status, 
          :warning_status,:department_id, :house_id, :worktype_id, :time_open,
          :time_work, :contact_fio, :contact_telephone, :contact_cellphone,
          :description, :number)");
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
    $sql->query(self::$sql_find);
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
    $sql->query(self::$sql_select_by_number);
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
      $query->verify('id', 'payment_status', 'warning_status',
                      'contact_fio', 'contact_telephone',
                      'close_reason', 'description', 'status',
                      'time_work', 'time_close', 'initiator');
      $query->get_house()->verify('id');
      $query->get_work_type()->verify('id');
      $sql = new sql();
      $sql->query("UPDATE `queries` SET `payment-status` = :payment_status,
                  `warning-type` = :warning_status, `addinfo-name` = :fio,
                  `addinfo-telephone` = :telephone,
                  `addinfo-cellphone` = :cellphone,
                  `description-close` = :close_reason,
                  `description-open` = :description,
                  `query_worktype_id` = :work_type_id,
                  `status` = :status, `worktime` = :time_work,
                  `closetime` = :time_close,
                  `house_id` = :house_id,
                  `initiator-type` = :initiator
                  WHERE `company_id` = :company_id AND `id` = :id");
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
    $sql->query("SELECT MAX(`id`) as `max_query_id` FROM `queries`
        WHERE `company_id` = :company_id");
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
    $sql->query("SELECT MAX(`querynumber`) as `querynumber` FROM `queries`
                WHERE `opentime` > :begin AND `opentime` <= :end
                AND `company_id` = :company_id");
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
    $sql->query("SELECT `queries`.`id`, `queries`.`company_id`,
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
      AND `queries`.`department_id` = `departments`.`id`");
      $sql->bind(':time_open', $params['time_open_begin'], PDO::PARAM_INT);
      $sql->bind(':time_close', $params['time_open_end'], PDO::PARAM_INT);
      if(!empty($params['status'])){
       $sql->query(" AND `queries`.`status` = :status");
       $sql->bind(':status', (string) $params['status'], PDO::PARAM_STR);
      }
      if(!empty($params['department'])){
      $sql->query(" AND `queries`.`department_id` = :department");
       $sql->bind(':department', (int)  $params['department'], PDO::PARAM_INT);
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
      // if(!empty($query->department_id)){
      //  if(is_array($query->department_id))
      //    $departments = $query->department_id;
      //  else
      //    $departments[] = $query->department_id;
      //  foreach($departments as $key => $department){
      //    $p_departments[] = ':department_id'.$key;
      //    $sql->bind(':department_id'.$key, $department, PDO::PARAM_INT);
      //  }
      //  $sql->query(" AND `queries`.`department_id` IN(".implode(',', $p_departments).")");
      // }
    $sql->query(" ORDER BY `queries`.`opentime` DESC");
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборки заявко по номеру.');
    $stmt = $sql->get_stm();
    $queries = [];
    while($row = $stmt->fetch())
      $queries[] = $this->create_object($row);
    return $queries;
  }
}