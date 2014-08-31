<?php
class controller_user{

	public static $name = 'Пользователи';

  public static function private_add_profile(model_request $request){
    $em = di::get('em');
    $user = $em->find('data_user', $request->take_get('user_id'));
    $p = new data_profile($user, $request->take_get('profile'));
    if(!empty(model_user2profile::$rules[$request->take_get('profile')]))
      $p->set_rules(model_user2profile::$rules[$request->take_get('profile')]);
    else
      $p->set_rules([]);
    if(!empty(model_user2profile::$restrictions[$request->take_get('profile')]))
      $p->set_restrictions(model_user2profile::$restrictions[$request->take_get('profile')]);
    else
      $p->set_restrictions([]);
    $user->add_profile($p);
    $em->persist($p);
    $em->flush();
    return ['user' => $user];
  }

  public static function private_add_user(model_request $request){
    $em = di::get('em');
    $group = $em->find('data_group', $request->take_get('group_id'));
    $user = $em->find('data_user', $request->take_get('user_id'));
    $group->add_user($user);
    $em->flush();
    return ['group' => $group];
  }

  public static function private_create_group(model_request $request){
    $em = di::get('em');
    if(!is_null($em->getRepository('data_group')->findOneByName($request->take_get('name'))))
      throw new RuntimeException('Группа с таким название уже существует.');
    $group = new data_group();
    $group->set_name($request->take_get('name'));
    $group->set_status('true');
    $em->persist($group);
    $em->flush();
    $letter_group = mb_strtolower(mb_substr($group->get_name(), 0 ,1, 'utf-8'), 'utf-8');
    $letters = [];
    $groups = $em->getRepository('data_group')->findAll();
    if(!empty($groups))
      foreach($groups as $group){
        $letter = mb_strtolower(mb_substr($group->get_name(), 0 ,1, 'utf-8'), 'utf-8');
        if($letter === $letter_group)
          $letters[] = $group;
      }
    return ['groups' => $letters];
  }

  public static function private_create_user(model_request $request){
    if($request->take_get('password') !== $request->take_get('confirm'))
      throw new RuntimeException('Пароль и подтверждение не равны.');
    if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $request->take_get('password')))
      throw new RuntimeException('Пароль не удовлетворяет a-zA-Z0-9 или меньше 8 символов.');
    $em = di::get('em');
    if(!is_null($em->getRepository('data_user')->findOneByLogin($login)))
      throw new RuntimeException();
    $user = new data_user();
    $user->set_lastname($request->take_get('lastname'));
    $user->set_firstname($request->take_get('firstname'));
    $user->set_middlename($request->take_get('middlename'));
    $user->set_login($request->take_get('login'));
    $user->set_hash(data_user::generate_hash($request->take_get('password')));
    $user->set_status($status);
    $em->persist($user);
    $em->flush();
    $letter_user = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
    $letter_users = [];
    $users = $em->getRepository('data_user')->findAll();
    if(!empty($users))
      foreach($users as $user){
        $letter = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
        if($letter === $letter_user)
          $letter_users[] = $user;
      }
    return ['users' => $letter_users];
  }

  public static function private_delete_profile(model_request $request){
    $em = di::get('em');
    $user = $em->find('data_user', $request->take_get('user_id'));
    $profile = $user->get_profile($request->take_get('profile'));
    $em->remove($profile);
    $em->flush();
    return true;
  }

  public static function private_clear_logs(model_request $request){
    return true;
  }

  public static function private_exclude_user(model_request $request){
    $em = di::get('em');
    $group = $em->find('data_group', $request->take_get('group_id'));
    $user = $em->find('data_user', $request->take_get('user_id'));
    $group->exclude_user($user);
    $em->flush();
    return ['group' => $group];
  }

  public static function private_get_profile_content(model_request $request){
    return ['user' => di::get('em')->find('data_user', $request->take_get('user_id')),
            'profile' => $request->take_get('profile')];
  }

  public static function private_get_restriction_content(model_request $request){
    $em = di::get('em');
    $user = $em->find('data_user', $request->take_get('user_id'));
    $profile = $request->GET('profile');
    $restriction = $request->GET('restriction');
    if($profile !== 'query')
        throw new RuntimeException('Нет ограничения для профиля.');
    if(!in_array($restriction, ['departments', 'worktypes']))
        throw new RuntimeException('Нет ограничения для профиля.');
    if($restriction === 'departments')
        $items = di::get('em')->getRepository('data_department')->findAll();
    if($restriction === 'worktypes')
        $items = $em->getRepository('data_query_work_type')->findAll();
    return ['user' => $user,
      'restriction_name' => $restriction, 'items' => $items,
      'profile' => $user->get_profile($request->GET('profile'))];
  }

  public static function private_get_dialog_create_group(
    model_request $request){
    return true;
  }

  public static function private_get_dialog_create_user(model_request $request){
    return true;
  }

  public static function private_get_dialog_clear_logs(model_request $request){
    return true;
  }

  public static function private_get_dialog_add_profile(model_request $request){
    return null;
  }

  public static function private_get_dialog_add_user(model_request $request){
    return ['users' => di::get('em')->getRepository('data_user')->findAll()];
  }

  public static function private_get_dialog_delete_profile(
    model_request $request){
    return true;
  }

  public static function private_get_dialog_edit_group_name(
    model_request $request){
    return ['group' => di::get('em')->find('data_group', $request->take_get('id'))];
  }

  public static function private_get_dialog_edit_fio(model_request $request){
    return ['user' => di::get('em')->find('data_user', $request->take_get('id'))];
  }

  public static function private_get_dialog_edit_password(
    model_request $request){
    return ['user' => di::get('em')->find('data_user', $request->take_get('id'))];
  }

  public static function private_get_dialog_edit_user_status(
    model_request $request){
    return ['user' => di::get('em')->find('data_user', $request->take_get('id'))];
  }

    public static function private_get_dialog_exclude_user(
      model_request $request){
      $em = di::get('em');
      return [
        'user' => $em->find('data_user', $request->take_get('user_id')),
        'group' => $em->find('data_group', $request->take_get('group_id'))];
    }

  public static function private_get_group_letters(model_request $request){
    $letters = [];
    $groups = di::get('em')->getRepository('data_group')->findAll();
    if(!empty($groups))
      foreach($groups as $group){
        $letter = mb_strtolower(mb_substr($group->get_name(), 0 ,1, 'utf-8'), 'utf-8');
        $letters[$letter]++;
      }
    return ['letters' => $letters];
  }

  public static function private_get_group_profile(model_request $request){
    return ['group' => di::get('em')->find('data_group', $request->take_get('id'))];
  }

  public static function private_get_group_users(model_request $request){
    return ['group' => di::get('em')->find('data_group', $request->take_get('id'))];
  }

  public static function private_get_dialog_edit_login(model_request $request){
    return ['user' => di::get('em')->find('data_user', $request->take_get('id'))];
  }

	public static function private_show_default_page(model_request $request){
    $letters = [];
    $users = di::get('em')->getRepository('data_user')->findAll();
    if(!empty($users))
      foreach($users as $user){
        $letter = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
        if(isset($letters[$letter]))
          $letters[$letter]++;
        else
          $letters[$letter] = 1;
      }
    return ['letters' => $letters];
	}

  public static function private_get_group_letter(model_request $request){
    $letters = [];
    $groups = di::get('em')->getRepository('data_group')->findAll();
    if(!empty($groups))
      foreach($groups as $group){
        $letter = mb_strtolower(mb_substr($group->get_name(), 0 ,1, 'utf-8'), 'utf-8');
        if($letter === $request->take_get('letter'))
          $letters[] = $group;
      }
    return ['groups' => $letters];
  }

  public static function private_logs(model_request $request){
    return ['sessions' => di::get('em')->getRepository('data_session')
      ->findby([], ['time' => 'ASC'])];
  }

  public static function private_get_user_letter(model_request $request){
    $letter_users = [];
    $users = di::get('em')->getRepository('data_user')->findAll();
    if(!empty($users))
      foreach($users as $user){
        $letter = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
        if($letter === $request->take_get('letter'))
          $letter_users[] = $user;
      }
    return ['users' => $letter_users];
  }

  public static function private_get_group_content(model_request $request){
    return ['group' => di::get('em')->find('data_group', $request->take_get('id'))];
  }

  public static function private_get_user_content(model_request $request){
    return ['user' => di::get('em')->find('data_user', $request->take_get('id'))];
  }

  public static function private_get_user_information(model_request $request){
    return ['user' => di::get('em')->find('data_user', $_GET['id'])];
  }

  public static function private_get_user_profiles(model_request $request){
    return ['user' => di::get('em')->find('data_user', $request->GET('id'))];
  }

  public static function private_get_user_letters(model_request $request){
    $letters = [];
    $users = di::get('em')->getRepository('data_user')->findAll();
    if(!empty($users))
      foreach($users as $user){
        $letter = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
        $letters[$letter]++;
      }
    return ['letters' => $letters];
  }

  public static function private_update_group_name(model_request $request){
    $em = di::get('em');
    $group = $em->find('data_group', $request->take_get('id'));
    $group->set_name($request->take_get('name'));
    $em->flush();
    return ['group' => $group];
  }

  public static function private_update_fio(model_request $request){
    $em = di::get('em');
    $user = $em->find('data_user', $request->take_get('id'));
    $user->set_lastname($request->take_get('lastname'));
    $user->set_firstname($request->take_get('firstname'));
    $user->set_middlename($request->take_get('middlename'));
    $em->flush();
    return ['user' => $user];
  }

  public static function private_update_password(model_request $request){
    if($request->take_get('password') !== $request->take_get('confirm'))
      throw new RuntimeException('Пароль и подтверждение не идентичны.');
    $em = di::get('em');
    $user = $em->find('data_user', $request->take_get('id'));
    $user->set_hash(data_user::generate_hash($request->take_get('password')));
    $em->flush();
    return ['user' => $user];
  }

  public static function private_update_rule(model_request $request){
    $em = di::get('em');
    $user = $em->find('data_user', $request->take_get('user_id'));
    $profile = $user->get_profile($request->take_get('profile'));
    $rules = $profile->get_rules();
    if(in_array($request->take_get('rule'), array_keys($rules))){
      $rules[$request->take_get('rule')] = !$rules[$request->take_get('rule')];
      $profile->set_rules($rules);
      $em->flush();
    }else
      throw new RuntimeException('Правила '.$rule.' нет в профиле '.$profile);
    return $rules[$rule];
  }

  public static function private_update_restriction(model_request $request){
    $em = di::get('em');
    $user = $em->find('data_user', $request->take_get('user_id'));
    $profile = $user->get_profile($request->take_get('profile'));
    $restrictions = $profile->get_restrictions();
    $restriction = $request->GET('restriction');
    $item = $request->GET('item');
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
      $type = $em->find('data_query_work_type', $item);
      if(!is_null($type)){
        $pos = array_search($type->get_id(), $restrictions[$restriction]);
        if($pos === false)
          $restrictions[$restriction][] = $type->get_id();
        else
          unset($restrictions[$restriction][$pos]);
        $restrictions[$restriction] = array_values($restrictions[$restriction]);
      }
    }
    $profile->set_restrictions($restrictions);
    $em->flush();
    exit();
  }

  public static function private_update_login(model_request $request){
    $em = di::get('em');
    $user = $em->getRepository('data_user')
      ->findOneByLogin($request->take_get('login'));
    if(!is_null($user))
      throw new RuntimeException();
    $user = $em->find('data_user', $request->take_get('id'));
    $user->set_login($request->take_get('login'));
    $em->flush();
    return ['user' => $user];
  }

  public static function private_update_user_status(model_request $request){
    $em = di::get('em');
    $user = $em->find('data_user', $request->take_get('id'));
    if($user->get_status() === 'true')
      $user->set_status('false');
    else
      $user->set_status('true');
    $em->flush();
    return ['user' => $user];
  }
}