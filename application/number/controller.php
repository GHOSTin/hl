<?php
class controller_number{

	static $name = 'Жилищный фонд';
  private static $params = [];

  public static function set_param($key, $value){
    self::$params = $_SESSION['controller'];
    self::$params[$key] = $value;
    $_SESSION['controller'] = self::$params;
  }

  public static function get_param($key){
    self::$params = $_SESSION['controller'];
    return self::$params[$key];
  }

  public static function private_edit_department(
    model_request $request){
    $em = di::get('em');
    $house = $em->find('data_house', $request->GET('house_id'));
    $department = $em->find('data_department', $request->GET('department_id'));
    $house->set_department($department);
    $em->flush();
    return ['house' => $house];
  }

	public static function private_show_default_page(model_request $request){
    return ['streets' => di::get('em')
      ->getRepository('data_street')->findBy([], ['name' => 'ASC'])];
	}

  public static function private_get_street_content(model_request $request){
    $street = di::get('em')->find('data_street', $request->GET('id'));
    return ['street' => $street];
  }

  public static function private_get_dialog_edit_department(
    model_request $request){
    $em = di::get('em');
    return ['house' => $em->find('data_house', $request->GET('house_id')),
            'departments' => $em->getRepository('data_department')->findAll()];
  }

  public static function private_get_house_content(model_request $request){
    return ['house' => di::get('em')
      ->find('data_house', $request->take_get('id'))];
  }

  public static function private_get_house_information(model_request $request){
    return ['house' => di::get('em')->find('data_house', $request->GET('id'))];
  }

  public static function private_get_house_numbers(model_request $request){
    return ['house' => di::get('em')->find('data_house', $request->GET('id'))];
  }

  public static function private_get_number_content(model_request $request){
      $company = di::get('company');
      $number = di::get('em')->find('data_number', $request->GET('id'));
      $switch = self::get_param('number_content');
      switch($switch){
        default:
          return ['number' => $number];
      }
  }

  public static function private_get_number_information(model_request $request){
      self::set_param('number_content', 'information');
      return ['number' => di::get('em')->find('data_number', $request->GET('id'))];
  }

  public static function private_get_dialog_edit_number(model_request $request){
    return self::data_for_dialog_number($request);
  }

  private static function data_for_dialog_number(model_request $request){
    return ['number' => di::get('em')
            ->find('data_number', $request->GET('id'))];
  }

  public static function private_accruals(
    model_request $request){
    $number = di::get('em')->find('data_number', $request->GET('id'));
    return ['number' => $number];
  }

  public static function private_get_dialog_edit_number_fio(
    model_request $request){
    return self::data_for_dialog_number($request);
  }

  public static function private_get_dialog_edit_password(
    model_request $request){
  }

  public static function private_get_dialog_edit_number_telephone(
    model_request $request){
    return self::data_for_dialog_number($request);
  }

  public static function private_get_dialog_edit_number_cellphone(
    model_request $request){
    return self::data_for_dialog_number($request);
  }

  public static function private_get_dialog_edit_number_email(
    model_request $request){
    return self::data_for_dialog_number($request);
  }

  public static function private_update_number(model_request $request){
    $em = di::get('em');
    $number = $em->find('data_number', $request->GET('id'));
    $old_number = $em->getRepository('data_number')
                      ->findByNumber($request->GET('number'));
    if(!is_null($old_number))
      if($number->get_id() != $old_number->get_id())
        throw new RuntimeException('В базе уже есть лицевой счет с таким номером.');
    $number->set_number($request->GET('number'));
    $em->flush();
    return ['number' => $number];
  }

  public static function private_update_number_password(model_request $request){
    if($request->GET('password') !== $request->GET('confirm'))
      throw new RuntimeException('Подтверждение и пароль не совпадают.');
    $em = di::get('em');
    $number = $em->find('data_number', $request->GET('id'));
    $hash = md5(md5(htmlspecialchars($request->GET('password'))).application_configuration::authSalt);
    $number->set_hash($hash);
    $em->flush();
    return ['number' => $number];
  }

  public static function private_update_number_fio(model_request $request){
    $em = di::get('em');
    $number = $em->find('data_number', $request->GET('id'));
    $number->set_fio($request->GET('fio'));
    $em->flush();
    return ['number' => $number];
  }

  public static function private_update_number_cellphone(
    model_request $request){
    preg_match_all('/[0-9]/', $request->GET('cellphone'), $matches);
    $cellphone = implode('', $matches[0]);
    if(preg_match('|^[78]|', $cellphone))
      $cellphone = substr($cellphone, 1, 10);
    $em = di::get('em');
    $number = $em->find('data_number', $request->GET('id'));
    $number->set_cellphone($cellphone);
    $em->flush();
    return ['number' => $number];
  }

  public static function private_update_number_email(
    model_request $request){
    preg_match_all('/[0-9A-Za-z.@-]/', $request->GET('email'), $matches);
    $em = di::get('em');
    $number = $em->find('data_number', $request->GET('id'));
    $number->set_email(implode('', $matches[0]));
    $em->flush();
    return ['number' => $number];
  }

  public static function private_update_number_telephone(model_request $request){
    $em = di::get('em');
    $number = $em->find('data_number', $request->GET('id'));
    $number->set_telephone($request->GET('telephone'));
    $em->flush();
    return ['number' => $number];
  }
}