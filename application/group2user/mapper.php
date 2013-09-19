<?php
class mapper_group2user{

  private $company;
  private $group;

  public function __construct(data_company $company, data_group $group){
    $this->company = $company;
    $this->group = $group;
    $this->company->verify('id');
    $this->group->verify('id');
  }

  public function get_users(){
    $sql = new sql();
    $sql->query("SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
          `users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
          `users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
          `users`.`cellphone`
          FROM `users`, `group2user` WHERE `group2user`.`group_id` = :group_id
          AND `users`.`id` = `group2user`.`user_id` ORDER BY `users`.`lastname`");
    $sql->bind(':group_id', (int) $this->group->get_id(), PDO::PARAM_INT);
    return $sql->map(new data_user(), 'Проблема при выборки пользователей группы.');
  }
}