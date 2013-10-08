<?php
class mapper_workgroup2work{

  private $company;
  private $work_group;

  private static $sql_get_works = "SELECT `id`,`company_id`, `status`, `name`
    FROM `works` WHERE `company_id` = :company_id
    AND `workgroup_id` = :work_group_id";

  public function __construct(data_company $company, data_workgroup $work_group){
    $this->company = $company;
    $this->work_group = $work_group;
    $this->company->verify('id');
    $this->work_group->verify('id');
  }

  public function create_object(array $row){
    $work = new data_work();
    $work->set_id($row['id']);
    $work->set_name($row['name']);
    return $work;
  }

  public function get_works(){
    $sql = new sql();
    $sql->query(self::$sql_get_works);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':work_group_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборки работ.');
    $stmt = $sql->get_stm();
    $works = [];
    while($row = $stmt->fetch())
      $works[] = $this->create_object($row);
    return $works;
  }

  public function init_works(){
    $works = $this->get_works();
    if(!empty($works))
      foreach($works as $work)
        $this->work_group->add_work($work);
      return $this->work_group;
  }
}