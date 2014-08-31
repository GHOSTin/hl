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
}