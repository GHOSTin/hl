<?php
class controller_user{

	public static $name = 'Пользователи';

  public static function private_add_profile(model_request $request){
    $company = di::get('em')
      ->find('data_company', $request->take_get('company_id'));
    $user = new data_user();
    $user->set_id($request->take_get('user_id'));
    (new model_user2profile($company, $user))
      ->add_profile($request->take_get('profile'));
    return ['companies' => (new mapper_user2company(di::get('pdo'), $user))
      ->find_all()];
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
    $group->set_company_id(di::get('company')->get_id());
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
    $user->set_company_id(di::get('company')->get_id());
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
    $company = di::get('em')
      ->find('data_company', $request->take_get('company_id'));
    $user = new data_user();
    $user->set_id($request->take_get('user_id'));
    (new model_user2profile($company, $user))
      ->delete($request->take_get('profile'));
    return true;
  }

  public static function private_clear_logs(model_request $request){
    di::get('mapper_session')->truncate();
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

  public static function private_get_company_content(model_request $request){
    $user = new data_user();
    $user->set_id($request->take_get('user_id'));
    $company = di::get('em')
      ->find('data_company', $request->take_get('company_id'));
    $profiles = (new model_user2profile($company, $user))->get_profiles();
    return ['user' => $user, 'company' => $company, 'profiles' => $profiles];
  }

  public static function private_get_profile_content(model_request $request){
    $company = di::get('em')
      ->find('data_company', $request->take_get('company_id'));
    $user = new data_user();
    $user->set_id($request->take_get('user_id'));
    return ['user' => $user, 'company' => $company,
            'profile' => (new model_user2profile($company, $user))
            ->get_profile($request->take_get('profile'))];
  }

  public static function private_get_restriction_content(model_request $request){
    $company = di::get('em')
      ->find('data_company', $request->take_get('company_id'));
    $user = new data_user();
    $user->set_id($request->GET('user_id'));
    $profile = $request->GET('profile');
    $restriction = $_GET['restriction'];
    if($profile !== 'query')
        throw new RuntimeException('Нет ограничения для профиля.');
    if(!in_array($restriction, ['departments', 'worktypes']))
        throw new RuntimeException('Нет ограничения для профиля.');
    if($restriction === 'departments')
        $items = (new model_department($company))->get_departments();
    if($restriction === 'worktypes')
        $items = (new model_query_work_type($company))->get_query_work_types();
    return ['user' => $user, 'company' => $company,
      'restriction_name' => $restriction, 'items' => $items,
      'profile' => (new model_user2profile($company, $user))
        ->get_profile($request->take_get('profile'))];
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
    return ['companies' => di::get('em')->getRepository('data_company')->findAll()];
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
    return ['sessions' => di::get('mapper_session')->find_all()];
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
    $user = new data_user();
    $user->set_id($request->GET('id'));
    return ['user' => $user,
      'companies' => (new mapper_user2company(di::get('pdo'), $user))
      ->find_all()];
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
    $company = di::get('em')
      ->find('data_company', $request->take_get('company_id'));
    $user = new data_user();
    $user->set_id($request->take_get('user_id'));
    (new model_user2profile($company, $user))
      ->update_rule($request->take_get('profile'), $request->take_get('rule'));
    exit();
  }

  public static function private_update_restriction(model_request $request){
    $company = di::get('em')
      ->find('data_company', $request->take_get('company_id'));
    $user = new data_user();
    $user->set_id($request->GET('user_id'));
    (new model_user2profile($company, $user))
      ->update_restriction($request->GET('profile'),
      $request->GET('restriction'), $request->GET('item'));
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