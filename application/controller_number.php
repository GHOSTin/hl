<?php

use \boxxy\classes\di;

class controller_number{

	static $name = 'Жилищный фонд';

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
    $houses = di::get('model_number')
      ->get_houses_by_street($request->GET('id'));
    return ['houses' => $houses];
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

  public static function get_number($id){
    return di::get('em')->find('data_number', $id);
  }

  public static function private_get_number_content(model_request $request){
    return ['number' => self::get_number($request->GET('id'))];
  }

  public static function private_contact_info(model_request $request){
    return ['number' => self::get_number($request->GET('id'))];
  }

  public static function private_get_dialog_edit_number(model_request $request){
    return ['number' => self::get_number($request->GET('id'))];
  }

  public static function private_accruals(
    model_request $request){
    return ['number' => self::get_number($request->GET('id'))];
  }

  public static function private_get_dialog_edit_number_fio(
    model_request $request){
    return ['number' => self::get_number($request->GET('id'))];
  }

  public static function private_get_dialog_edit_password(
    model_request $request){
    return ['number' => self::get_number($request->GET('id'))];
  }

  public static function private_get_dialog_edit_number_telephone(
    model_request $request){
    return ['number' => self::get_number($request->GET('id'))];
  }

  public static function private_get_dialog_edit_number_cellphone(
    model_request $request){
    return ['number' => self::get_number($request->GET('id'))];
  }

  public static function private_get_dialog_edit_number_email(
    model_request $request){
    return ['number' => self::get_number($request->GET('id'))];
  }

  public static function private_update_number(model_request $request){
    $number = self::get_number($request->GET('id'));
    $em = di::get('em');
    $old_number = $em->getRepository('data_number')
                      ->findOneByNumber($request->GET('number'));
    if(!is_null($old_number))
      if($number->get_id() !== $old_number->get_id())
        throw new RuntimeException('Number exists.');
    $number->set_number($request->GET('number'));
    $em->flush();
    return ['number' => $number];
  }

  public static function private_update_number_password(model_request $request){
    $password = $request->GET('password');
    $confirm = $request->GET('confirm');
    if($password !== $confirm)
      throw new RuntimeException('Подтверждение и пароль не совпадают.');
    $number = self::get_number($request->GET('id'));
    $hash = data_number::generate_hash($password);
    $number->set_hash($hash);
    di::get('em')->flush();
    return ['number' => $number];
  }

  public static function private_update_number_fio(model_request $request){
    $number = self::get_number($request->GET('id'));
    $number->set_fio($request->GET('fio'));
    di::get('em')->flush();
    return ['number' => $number];
  }

  public static function private_update_number_cellphone(
    model_request $request){
    preg_match_all('/[0-9]/', $request->GET('cellphone'), $matches);
    $cellphone = implode('', $matches[0]);
    if(preg_match('|^[78]|', $cellphone))
      $cellphone = substr($cellphone, 1, 10);
    $number = self::get_number($request->GET('id'));
    $number->set_cellphone($cellphone);
    di::get('em')->flush();
    return ['number' => $number];
  }

  public static function private_update_number_email(
    model_request $request){
    preg_match_all('/[0-9A-Za-z.@-]/', $request->GET('email'), $matches);
    $number = self::get_number($request->GET('id'));
    $number->set_email(implode('', $matches[0]));
    di::get('em')->flush();
    return ['number' => $number];
  }

  public static function private_update_number_telephone(model_request $request){
    $number = self::get_number($request->GET('id'));
    $number->set_telephone($request->GET('telephone'));
    di::get('em')->flush();
    return ['number' => $number];
  }
}