<?php
class mapper_workgroup2work{

  private $company;
  private $pdo;
  private $group;

  private static $alert = "Проблема в мапере работ.";

  private static $many = "SELECT `id`,`company_id`, `status`, `name`
    FROM `works` WHERE `company_id` = :company_id
    AND `workgroup_id` = :group_id";

  public function __construct(data_company $company,
    data_workgroup $work_group){
    $this->company = $company;
    $this->group = $work_group;
    data_company::verify_id($this->company->get_id());
    data_workgroup::verify_id($this->group->get_id());
    $this->pdo = di::get('pdo');
  }

  private function get_works(){
    $stmt = $this->pdo->prepare(self::$many);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':group_id', (int) $this->group->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $works = [];
    $factory = new factory_work();
    while($row = $stmt->fetch())
      $works[] = $factory->create($row);
    return $works;
  }

  public function init_works(){
    $works = $this->get_works();
    if(!empty($works))
      foreach($works as $work)
        $this->group->add_work($work);
    return $this->group;
  }
}