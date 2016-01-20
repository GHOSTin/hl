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

  public function default_page(Application $app){
    return $app['main\models\users']->default_page();
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

  public function get_group_users(Request $request, Application $app){
    $group = $app['em']->find('domain\group', $request->get('id'));
    return $app['twig']->render('user\get_group_users.tpl', ['group' => $group]);
  }

  public function get_users(Application $app){
    return $app['main\models\users']->get_users();
  }

  public function access(Application $app, $id){
    $user = $app['em']->find('domain\user', $id);
    return $app['twig']->render('user\access.tpl', ['user' => $user]);
  }

  public function create_user(Request $request, Application $app){
    $password = $request->get('password');
    $confirm = $request->get('confirm');
    $login = $request->get('login');
    if($password !== $confirm)
      throw new RuntimeException('Пароль и подтверждение не идентичны.');
    if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $password))
      throw new RuntimeException('Пароль не удовлетворяет a-zA-Z0-9 или меньше 8 символов.');
    $app['main\models\users']->create_user(
      $request->get('lastname'),
      $request->get('firstname'),
      $request->get('middlename'),
      $login,
      user::generate_hash($password, $app['salt'])
    );
    return $app['main\models\users']->get_users();
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
    return $app['main\models\users']->get_dialog_create_user();
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