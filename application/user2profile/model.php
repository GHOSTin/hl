<?php
class model_user2profile{

  private $user;

  public static $rules = [
    'import' => ['generalAccess' => false],
    'number' => ['generalAccess' => false,
                'createDepartment' => false,
                'createNumber' => false,
                'editMeter' => false,
                'editNumber' => false,
                'editNumberContact' => false,
                'sendSms' => false],
    'report' => ['generalAccess' => false],
    'query' => ['generalAccess' => false,
                'createQuery' => false,
                'closeQuery' => false,
                'editContact' => false,
                'editDescriptionOpen' => false,
                'editDescriptionClose' => false,
                'editInitiator' => false,
                'editPaymentStatus' => false,
                'editPerformers' => false,
                'editManagers' => false,
                'editMaterials' => false,
                'editNumbers' => false,
                'editWarningType' => false,
                'editWorks' => false,
                'editWorkType' => false,
                'editInspection' => false,
                'reopenQuery' => false,
                'sendSms' => false,
                'showHistory' => false,
                'showDocs' => false],
    'user' => ['generalAccess' => false,
              'addGroup' => false,
              'addUser' => false,
              'createGroup' => false,
              'createUser' => false,
              'deleteGroup' => false,
              'deleteUser' => false,
              'editGroup' => false,
              'editUser' => false,
              'setPassword' => false,
              'setRule' => false]
  ];

  public static $restrictions = [
    'query' =>['departments' => [],
                'worktypes' => []
              ]
  ];

  private static $update_restriction = 'UPDATE `profiles`
    SET `restrictions` = :restrictions
    WHERE `company_id` = :company_id
    AND `user_id` = :user_id AND `profile` = :profile';

  public function update_restriction($profile, $restriction, $item){
    $profile = $this->get_profile($profile);
    $restrictions = $profile->get_restrictions();
    if(!array_key_exists($restriction, $restrictions))
      throw new RuntimeException('Нет такого ограничения.');
    if($restriction === 'departments'){
      $department = di::get('em')->find('data_department', $item);
      if(!is_null($department)){
        $pos = array_search($department->get_id(), $restrictions[$restriction]);
        if($pos === false)
          $restrictions[$restriction][] = $department->get_id();
        else
          unset($restrictions[$restriction][$pos]);
        $restrictions[$restriction] = array_values($restrictions[$restriction]);
      }
    }
    if($restriction === 'worktypes'){
      $type = (new mapper_query_work_type)->find($item);
      if(!is_null($type)){
        $pos = array_search($type->get_id(), $restrictions[$restriction]);
        if($pos === false)
          $restrictions[$restriction][] = $type->get_id();
        else
          unset($restrictions[$restriction][$pos]);
        $restrictions[$restriction] = array_values($restrictions[$restriction]);
      }
    }
    $stmt = $this->pdo->prepare(self::$update_restriction);
    $stmt->bindValue(':restrictions', (string) json_encode($restrictions), PDO::PARAM_STR);
    $stmt->bindValue(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':profile', (string) $profile, PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
  }
}