<?php namespace main\controllers;

use RuntimeException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use domain\user;
use domain\group;

class users{

  public function add_user(Request $request, Application $app){
    $group = $app['em']->find('domain\group', $request->get('group_id'));
    $user = $app['em']->find('domain\user', $request->get('user_id'));
    $group->add_user($user);
    $app['em']->flush();
    return $app['twig']->render('user\add_user.tpl', ['group' => $group]);
  }

  public function create_group(Request $request, Application $app){
    $name = $request->get('name');
    if(!is_null($app['em']->getRepository('\domain\group')->findOneByName($name)))
      throw new RuntimeException('Группа с таким название уже существует.');
    $group = new group();
    $group->set_name($name);
    $group->set_status('true');
    $app['em']->persist($group);
    $app['em']->flush();
    $letter = mb_substr($group->get_name(), 0 ,1, 'utf-8');
    $letter_group = mb_strtolower($letter, 'utf-8');
    $letters = [];
    $groups = $app['em']->getRepository('\domain\group')->findAll();
    if(!empty($groups))
      foreach($groups as $group){
        $letter = mb_substr($group->get_name(), 0 ,1, 'utf-8');
        $letter = mb_strtolower($letter, 'utf-8');
        if($letter === $letter_group)
          $letters[] = $group;
      }
    return $app['twig']->render('user/get_group_letter.tpl',
                                ['groups' => $letters]);
  }

  public function default_page(Application $app){
    $letters = [];
    $users = $app['em']->getRepository('\domain\user')->findAll();
    if(!empty($users))
      foreach($users as $user){
        $letter = mb_substr($user->get_lastname(), 0 ,1, 'utf-8');
        $letter = mb_strtolower($letter, 'utf-8');
        if(isset($letters[$letter]))
          $letters[$letter]++;
        else
          $letters[$letter] = 1;
      }
    return $app['twig']->render('user\default_page.tpl',
                                [
                                 'user' => $app['user'],
                                 'letters' => $letters
                                ]);
  }

  public function exclude_user(Request $request, Application $app){
    $group = $app['em']->find('domain\group', $request->get('group_id'));
    $user = $app['em']->find('domain\user', $request->get('user_id'));
    $group->exclude_user($user);
    $app['em']->flush();
    return $app['twig']->render('user/add_user.tpl', ['group' => $group]);
  }

  public function get_group_content(Request $request, Application $app){
    $group = $app['em']->find('domain\group', $request->get('id'));
    return $app['twig']->render('user\get_group_content.tpl', ['group' => $group]);
  }

  public function get_group_profile(Request $request, Application $app){
    $group = $app['em']->find('domain\group', $request->get('id'));
    return $app['twig']->render('user\get_group_profile.tpl', ['group' => $group]);
  }

  public function get_group_letter(Request $request, Application $app){
    $letters = [];
    $groups = $app['em']->getRepository('\domain\group')->findAll();
    if(!empty($groups))
      foreach($groups as $group){
        $letter = mb_strtolower(mb_substr($group->get_name(), 0 ,1, 'utf-8'), 'utf-8');
        if($letter === $request->get('letter'))
          $letters[] = $group;
      }
    return $app['twig']->render('user\get_group_letter.tpl', ['groups' => $letters]);
  }

  public function get_group_letters(Request $request, Application $app){
    $letters = [];
    $groups = $app['em']->getRepository('\domain\group')->findAll();
    if(!empty($groups))
      foreach($groups as $group){
        $letter = mb_strtolower(mb_substr($group->get_name(), 0 ,1, 'utf-8'), 'utf-8');
        if(isset($letters[$letter]))
          $letters[$letter]++;
        else
          $letters[$letter] = 1;
      }
    return $app['twig']->render('user\get_group_letters.tpl', ['letters' => $letters]);
  }

  public function get_group_users(Request $request, Application $app){
    $group = $app['em']->find('domain\group', $request->get('id'));
    return $app['twig']->render('user\get_group_users.tpl', ['group' => $group]);
  }

  public function get_user_letter(Request $request, Application $app){
    $letter_users = [];
    $users = $app['em']->getRepository('\domain\user')->findAll();
    if(!empty($users))
      foreach($users as $user){
        $letter = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
        if($letter === $request->get('letter'))
          $letter_users[] = $user;
      }
    return $app['twig']->render('user\get_user_letter.tpl', ['users' => $letter_users]);
  }

  public function get_user_letters(Request $request, Application $app){
    $letters = [];
    $users = $app['em']->getRepository('\domain\user')->findAll();
    if(!empty($users))
      foreach($users as $user){
        $letter = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
        if(isset($letters[$letter]))
          $letters[$letter]++;
        else
          $letters[$letter] = 1;
      }
    return $app['twig']->render('user\get_user_letters.tpl', ['letters' => $letters]);
  }

  public function access(Application $app, $id){
    $user = $app['em']->find('domain\user', $id);
    return $app['twig']->render('user\access.tpl', ['user' => $user]);
  }

  public function get_dialog_clear_logs(Application $app){
    return $app['twig']->render('user\get_dialog_clear_logs.tpl');
  }

  public function get_dialog_create_group(Application $app){
    return $app['twig']->render('user\get_dialog_create_group.tpl');
  }

  public function logs(Request $request, Application $app){
    $sessions = $app['em']->getRepository('\domain\session')
                          ->findAll(['time' => 'ASC']);
    return $app['twig']->render('user\logs.tpl',
                                [
                                 'user' => $app['user'],
                                 'sessions' => $sessions
                                ]);
  }

  public function create_user(Request $request, Application $app){
    $password = $request->get('password');
    $confirm = $request->get('confirm');
    $login = $request->get('login');
    if($password !== $confirm)
      throw new RuntimeException('Пароль и подтверждение не идентичны.');
    if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $password))
      throw new RuntimeException('Пароль не удовлетворяет a-zA-Z0-9 или меньше 8 символов.');
    if(!is_null($app['em']->getRepository('\domain\user')->findOneByLogin($login)))
      throw new RuntimeException();
    $user = new user();
    $user->set_lastname($request->get('lastname'));
    $user->set_firstname($request->get('firstname'));
    $user->set_middlename($request->get('middlename'));
    $user->set_login($login);
    $user->set_hash(user::generate_hash($password, $app['salt']));
    $user->set_status('true');
    $app['em']->persist($user);
    $app['em']->flush();
    $letter_user = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
    $letter_users = [];
    $users = $app['em']->getRepository('\domain\user')->findAll();
    if(!empty($users))
      foreach($users as $user){
        $letter = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
        if($letter === $letter_user)
          $letter_users[] = $user;
      }
    return $app['twig']->render('user\get_user_letters.tpl', ['letters' => $letter_users]);
  }

  public function clear_logs(Request $request, Application $app){
    $sessions = $app['em']->getRepository('\domain\session')
                          ->findAll();
    foreach($sessions as $session)
      $app['em']->remove($session);
    $app['em']->flush();
    return $app['twig']->render('user\clear_logs.tpl');
  }

  public function get_dialog_add_user(Request $request, Application $app){
    $users = $app['em']->getRepository('domain\user')
                       ->findAll();
    $group = $app['em']->find('domain\group', $request->get('id'));
    return $app['twig']->render('user\get_dialog_add_user.tpl',
                                [
                                 'users' => $users,
                                 'group' => $group
                                ]);
  }

  public function get_dialog_create_user(Application $app){
    return $app['twig']->render('user\get_dialog_create_user.tpl');
  }

  public function get_user_content(Request $request, Application $app){
    $user = $app['em']->find('domain\user', $request->get('id'));
    return $app['twig']->render('user\get_user_content.tpl', ['user' => $user]);
  }

  public function get_user_information(Request $request, Application $app){
    $user = $app['em']->find('domain\user', $request->get('id'));
    return $app['twig']->render('user\get_user_information.tpl', ['user' => $user]);
  }

  public function get_dialog_edit_fio(Request $request, Application $app){
    $user = $app['em']->find('domain\user', $request->get('id'));
    return $app['twig']->render('user\get_dialog_edit_fio.tpl', ['user' => $user]);
  }

  public function get_dialog_edit_group_name(Request $request, Application $app){
    $group = $app['em']->find('domain\group', $request->get('id'));
    return $app['twig']->render('user\get_dialog_edit_group_name.tpl', ['group' => $group]);
  }

  public function get_dialog_edit_login(Request $request, Application $app){
    $user = $app['em']->find('domain\user', $request->get('id'));
    return $app['twig']->render('user\get_dialog_edit_login.tpl', ['user' => $user]);
  }

  public function get_dialog_edit_user_status(Request $request, Application $app){
    $user = $app['em']->find('domain\user', $request->get('id'));
    return $app['twig']->render('user\get_dialog_edit_user_status.tpl', ['user' => $user]);
  }

  public function get_dialog_edit_password(Request $request, Application $app){
    $user = $app['em']->find('domain\user', $request->get('id'));
    return $app['twig']->render('user\get_dialog_edit_password.tpl', ['user' => $user]);
  }

  public function get_dialog_exclude_user(Request $request, Application $app){
    $user = $app['em']->find('domain\user', $request->get('user_id'));
    $group = $app['em']->find('domain\group', $request->get('group_id'));
    return $app['twig']->render('user\get_dialog_exclude_user.tpl',
                                [
                                 'user' => $user,
                                 'group' => $group
                                ]);
  }

  public function get_restrictions(Application $app, $id){
    $user = $app['em']->find('domain\user', $id);
    $departments = $app['em']->getRepository('domain\department')
                             ->findAll(['name' => 'ASC']);
    $categories = $app['em']->getRepository('domain\workgroup')
                             ->findAll(['name' => 'ASC']);
    return $app['twig']->render('user\get_restrictions.tpl',
                                [
                                  'user' => $user,
                                  'departments' => $departments,
                                  'categories' => $categories
                                ]);
  }

  public function update_fio(Request $request, Application $app){
    $user = $app['em']->find('domain\user', $request->get('id'));
    $user->set_lastname($request->get('lastname'));
    $user->set_firstname($request->get('firstname'));
    $user->set_middlename($request->get('middlename'));
    $app['em']->flush();
    return $app['twig']->render('user\update_fio.tpl', ['user' => $user]);
  }

  public function update_group_name(Request $request, Application $app){
    $group = $app['em']->find('domain\group', $request->get('id'));
    $group->set_name($request->get('name'));
    $app['em']->flush();
    return $app['twig']->render('user\update_group_name.tpl', ['group' => $group]);
  }

  public function update_login(Request $request, Application $app){
    $login = $request->get('login');
    $user = $app['em']->getRepository('domain\user')
                      ->findOneByLogin($login);
    if(!is_null($user))
      throw new RuntimeException();
    $user = $app['em']->find('domain\user', $request->get('id'));
    $user->set_login($login);
    $app['em']->flush();
    return $app['twig']->render('user\update_fio.tpl', ['user' => $user]);
  }

  public function update_user_status(Request $request, Application $app){
    $user = $app['em']->find('domain\user', $request->get('id'));
    if($user->get_status() === 'true')
      $user->set_status('false');
    else
      $user->set_status('true');
    $app['em']->flush();
    return $app['twig']->render('user\update_fio.tpl', ['user' => $user]);
  }

  public function update_password(Request $request, Application $app){
    $password = $request->get('password');
    $confirm = $request->get('confirm');
    if($password !== $confirm)
      throw new RuntimeException('Пароль и подтверждение не идентичны.');
    $user = $app['em']->find('domain\user', $request->get('id'));
    $user->set_hash(user::generate_hash($password, $app['salt']));
    $app['em']->flush();
    return $app['twig']->render('user\update_fio.tpl', ['user' => $user]);
  }

  public function update_restriction(Application $app, $id, $profile, $item){
    $user = $app['em']->find('domain\user', $id);
    $user->update_restriction($profile, $item);
    $app['em']->flush();
    return new Response();
  }

  public function update_access(Application $app, $id, $profile, $rule){
    $user = $app['em']->find('domain\user', $id);
    $user->update_access($profile.'/'.$rule);
    $app['em']->flush();
    return new Response();
  }
}