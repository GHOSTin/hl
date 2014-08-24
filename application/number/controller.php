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

  public static function private_add_meter(model_request $request){
    $date_release = explode('.', $request->GET('date_release'));
    $date_install = explode('.', $request->GET('date_install'));
    $date_checking = explode('.', $request->GET('date_checking'));
    $number = di::get('em')->find('data_number', $request->GET('number_id'));
    (new model_number2meter(di::get('company'), $number))
      ->add_meter($request->GET('meter_id'), $request->GET('serial'),
      $request->GET('service'), $request->GET('place'),
      mktime(12, 0, 0, $date_release[1], $date_release[0], $date_release[2]),
      mktime(12, 0, 0, $date_install[1], $date_install[0], $date_install[2]),
      mktime(12, 0, 0, $date_checking[1], $date_checking[0],$date_checking[2]),
      $request->GET('period'), $request->GET('comment'));
    $enable_meters = $disable_meters = [];
    if(!empty($number->get_meters()))
      foreach($number->get_meters() as $meter)
        if($meter->get_status() == 'enabled')
          $enable_meters[] = $meter;
        elseif($meter->get_status() == 'disabled')
          $disable_meters[] = $meter;
    return ['number' => $number, 'enable_meters' => $enable_meters,
            'disable_meters' => $disable_meters];
  }

  public static function private_add_house_processing_center(
    model_request $request){
    $house = di::get('em')->find('data_house', $request->GET('house_id'));
    $house = (new model_house2processing_center(
      di::get('company'), $house))
      ->add_processing_center($request->GET('center_id'),
      $request->GET('identifier'));
    return ['house' => $house];
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

  public static function private_remove_house_processing_center(
    model_request $request){
    $house = di::get('em')->find('data_house', $request->GET('house_id'));
    return ['house' => (new model_house2processing_center(
      di::get('company'), $house))
      ->remove_processing_center($request->GET('center_id'))];
  }

  public static function private_add_processing_center(model_request $request){
    $number = di::get('em')->find('data_number', $request->GET('number_id'));
    (new model_number2processing_center(di::get('company'), $number))
      ->add_processing_center($request->GET('center_id'),
        $request->GET('identifier'));
    return ['number' => $number];
  }

  public static function private_change_meter(model_request $request){
    $company = di::get('company');
    $number = di::get('em')->find('data_number', $request->GET('number_id'));
    $model = new model_number2meter($company, $number);
    $model->change_meter($request->GET('meter_id'), $request->GET('serial'),
      $request->GET('new_meter_id'), $request->GET('service'),
      $request->GET('period'));
    $model->init_meters();
    $enable_meters = $disable_meters = [];
    if(!empty($number->get_meters()))
        foreach($number->get_meters() as $meter)
            if($meter->get_status() == 'enabled')
                $enable_meters[] = $meter;
            elseif($meter->get_status() == 'disabled')
                $disable_meters[] = $meter;
    self::set_param('number_content', 'meters');
    return ['number' => $number,
            'enable_meters' => $enable_meters,
            'disable_meters' => $disable_meters];
  }

  public static function private_delete_meter(model_request $request){
    $number = di::get('em')->find('data_number', $request->GET('number_id'));
    (new model_number2meter(di::get('company'), $number))
      ->delete_meter($request->GET('meter_id'), $request->GET('serial'));
    $enable_meters = $disable_meters = [];
    if(!empty($number->get_meters()))
      foreach($number->get_meters() as $meter)
        if($meter->get_status() == 'enabled')
          $enable_meters[] = $meter;
        elseif($meter->get_status() == 'disabled')
          $disable_meters[] = $meter;
    return ['number' => $number, 'enable_meters' => $enable_meters,
            'disable_meters' => $disable_meters];
  }

  public static function private_exclude_processing_center(
    model_request $request){
    $number = di::get('em')->find('data_number', $request->GET('number_id'));
    (new model_number2processing_center(di::get('company'), $number))
      ->delete_processing_center($request->GET('center_id'));
    return ['number' => $number];
  }

	public static function private_show_default_page(model_request $request){
    return ['streets' => di::get('em')->getRepository('data_street')->findAll()];
	}

  public static function private_get_street_content(model_request $request){
    $street = di::get('em')->find('data_street', $request->GET('id'));
    return ['street' => $street];
  }

  public static function private_get_dialog_add_meter(model_request $request){
    return true;
  }

  public static function private_get_dialog_add_house_processing_center(
    model_request $request){
    return ['centers' => (new model_processing_center)
      ->get_processing_centers()];
  }

  public static function private_get_dialog_remove_house_processing_center(
    model_request $request){
    $house = di::get('em')->find('data_house', $request->GET('house_id'));
    (new mapper_house2processing_center(
      di::get('company'), $house))->init_processing_centers();
    return ['house' => $house];
  }

  public static function private_get_dialog_add_meter_option(
    model_request $request){
    $time = getdate();
    return ['meter' => (new model_meter(di::get('company')))
      ->get_meter($request->GET('meter_id')),
      'time' => $time['mday'].'.'.$time['mon'].'.'.$time['year']];
  }

  public static function private_get_dialog_add_processing_center(
    model_request $request){
    return ['centers' => (new model_processing_center)
      ->get_processing_centers()];
  }

  public static function private_get_dialog_edit_department(
    model_request $request){
    $em = di::get('em');
    return ['house' => $em->find('data_house', $request->GET('house_id')),
            'departments' => $em->getRepository('data_department')->findAll()];
  }

  public static function private_get_dialog_change_meter(
    model_request $request){
    return self::data_for_meters_dialog($request);
  }

  public static function private_get_dialog_delete_meter(
    model_request $request){
    return self::data_for_meters_dialog($request);
  }

  public static function private_get_dialog_edit_date_checking(
    model_request $request){
    return self::data_for_meters_dialog($request);
  }

  public static function private_get_dialog_edit_date_install(
    model_request $request){
    return self::data_for_meters_dialog($request);
  }

  public static function private_get_dialog_edit_date_release(
    model_request $request){
    return self::data_for_meters_dialog($request);
  }

  public static function private_get_dialog_edit_meter_comment(
    model_request $request){
    return self::data_for_meters_dialog($request);
  }

  public static function private_get_dialog_edit_period(
    model_request $request){
    return self::data_for_meters_dialog($request);
  }

  public static function private_get_dialog_edit_meter_status(
    model_request $request){
    return self::data_for_meters_dialog($request);
  }

  public static function private_get_dialog_edit_meter_place(
    model_request $request){
    return self::data_for_meters_dialog($request);
  }

  private static function data_for_meters_dialog(model_request $request){
    $number = di::get('em')->find('data_number', $request->GET('id'));
    $meter = (new model_number2meter(di::get('company'), $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_get_dialog_edit_serial(
    model_request $request){
    return self::data_for_meters_dialog($request);
  }

  public static function private_get_dialog_exclude_processing_center(
    model_request $request){
    return ['center' => (new model_processing_center)
                        ->get_processing_center($request->GET('center_id'))];
  }

  public static function private_get_house_content(model_request $request){
    return ['house' => di::get('em')
      ->find('data_house', $request->take_get('id'))];
  }

  public static function private_get_house_information(model_request $request){
    $house = di::get('em')->find('data_house', $request->GET('id'));
    (new model_house2processing_center(
      di::get('company'), $house))->init_processing_centers();
    return ['house' => $house];
  }

  public static function private_get_house_numbers(model_request $request){
    return ['house' => di::get('em')->find('data_house', $request->GET('id'))];
  }

  public static function private_get_meters(model_request $request){
    $company = di::get('company');
    $number = di::get('em')->find('data_number', $request->GET('id'));
    $model = new model_number2meter($company, $number);
    $model->init_meters();
    $enable_meters = $disable_meters = [];
    if(!empty($number->get_meters()))
      foreach($number->get_meters() as $meter)
        if($meter->get_status() == 'enabled')
          $enable_meters[] = $meter;
        elseif($meter->get_status() == 'disabled')
          $disable_meters[] = $meter;
    self::set_param('number_content', 'meters');
    return ['number' => di::get('em')->find('data_number', $request->GET('id')),
            'enable_meters' => $enable_meters,
            'disable_meters' => $disable_meters];
  }

  public static function private_get_number_content(model_request $request){
      $company = di::get('company');
      $number = di::get('em')->find('data_number', $request->GET('id'));
      $switch = self::get_param('number_content');
      switch($switch){
        case 'meters':
          $model = new model_number2meter($company, $number);
          $model->init_meters();
          $enable_meters = $disable_meters = [];
          if(!empty($number->get_meters()))
            foreach($number->get_meters() as $meter)
              if($meter->get_status() == 'enabled')
                $enable_meters[] = $meter;
              elseif($meter->get_status() == 'disabled')
                $disable_meters[] = $meter;
          return ['number' => $number,
            'enable_meters' => $enable_meters,
            'disable_meters' => $disable_meters,
            'setting' => $switch];
        break;

        case 'processing_centers':
            (new model_number2processing_center($company, $number))
              ->init_processing_centers();
            return ['number' => $number, 'setting' => $switch];
        break;

        default:
          return ['number' => $number];
      }
  }

  public static function private_get_number_information(model_request $request){
      self::set_param('number_content', 'information');
      return ['number' => di::get('em')->find('data_number', $request->GET('id'))];
  }

  public static function private_get_meter_data(model_request $request){
    $company = di::get('company');
    $number = di::get('em')->find('data_number', $request->GET('id'));
    $meter = (new model_number2meter($company, $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    if($request->GET('time') > 0)
      $time = getdate($request->GET('time'));
    else
      $time = getdate();
    $begin = mktime(12, 0, 0, 1, 1, $time['year']);
    $end = mktime(12, 0, 0, 12, 1, $time['year']);
    (new model_meter2data($company, $number, $meter))
      ->init_values($begin, $end);
    return ['number' => $number, 'meter' => $meter, 'time' => $begin];
  }

  public static function private_get_meter_periods(model_request $request){
    return ['meter' => (new model_meter(di::get('company')))
      ->get_meter($request->GET('meter_id'))];
  }

  public static function private_get_meter_value(model_request $request){
    $data = self::private_get_meter_data($request);
    $begin = mktime(12, 0, 0, 1, 1, $time['year']);
    $end = mktime(12, 0, 0, 12, 1, $time['year']);
    (new model_meter2data(di::get('company'),
      $data['number'], $data['meter']))->init_values($begin, $end);
    return $data;
  }

  public static function private_get_meter_cart(model_request $request){
    $company = di::get('company');
    $number = di::get('em')->find('data_number', $request->GET('number_id'));
    $meter = (new model_number2meter(di::get('company'), $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_get_meter_docs(model_request $request){
    return true;
  }

  public static function private_get_meter_info(model_request $request){
    $number = di::get('em')->find('data_number', $request->GET('id'));
    $meter = (new model_number2meter(di::get('company'), $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_get_meter_options(model_request $request){
    return ['meters' => (new model_meter(di::get('company')))
    ->get_meters_by_service($request->GET('service'))];
  }

  public static function private_get_processing_centers(model_request $request){
    $number = di::get('em')->find('data_number', $request->GET('id'));
    (new model_number2processing_center(di::get('company'), $number))
      ->init_processing_centers();
    self::set_param('number_content', 'processing_centers');
    return ['number' => $number];
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
    $accruals = di::get('mapper_accrual')
      ->find_all(di::get('company'), $number);
    return ['number' => $number, 'accruals' => $accruals];
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

  public static function private_get_dialog_edit_meter_data(
    model_request $request){
    $company = di::get('company');
    $number = di::get('em')->find('data_number', $request->GET('id'));
    $meter = (new model_number2meter($company, $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['number' => $number, 'meter' => $meter,
            'time' => $request->GET('time'), 'last_data' => null,
            'meter_data' => (new model_meter2data($company, $number, $meter))
              ->get_value($request->GET('time'))];
  }

  public static function private_update_date_checking(model_request $request){
    $time = explode('.', $request->GET('date'));
    $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
    $number = di::get('em')->find('data_number', $request->GET('number_id'));
    $meter = (new model_number2meter(di::get('company'), $number))
      ->update_date_checking($request->GET('meter_id'),
      $request->GET('serial'), $time);
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_update_date_install(model_request $request){
    $time = explode('.', $request->GET('date'));
    $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
    $number = di::get('em')->find('data_number', $request->GET('number_id'));
    $meter = (new model_number2meter(di::get('company'), $number))
      ->update_date_install($request->GET('meter_id'),
      $request->GET('serial'), $time);
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_update_date_release(model_request $request){
    $time = explode('.', $request->GET('date'));
    $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
    $number = di::get('em')->find('data_number', $request->GET('number_id'));
    $meter = (new model_number2meter(di::get('company'), $number))
      ->update_date_release($request->GET('meter_id'),
      $request->GET('serial'), $time);
    return ['number' => $number, 'meter' => $meter];
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

  public static function private_update_meter_data(model_request $request){
    $timestamp = explode('.', $request->GET('timestamp'));
    $timestamp = mktime(12, 0, 0, (int) $timestamp[1], (int) $timestamp[0], (int) $timestamp[2]);
    $company = di::get('company');
    $number = di::get('em')->find('data_number', $request->GET('id'));
    $meter = (new model_number2meter($company, $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));

    (new model_meter2data($company, $number, $meter))
      ->update_value($request->GET('time'), $request->GET('tarif'),
        $request->GET('way'), $request->GET('comment'), $timestamp);
    if($request->GET('time') > 0)
        $time = getdate($request->GET('time'));
    else
        $time = getdate();
    $begin = mktime(12, 0, 0, 1, 1, $time['year']);
    $end = mktime(12, 0, 0, 12, 1, $time['year']);
    (new model_meter2data($company, $number, $meter))->init_values($begin, $end);
    return ['number' => $number, 'meter' => $meter, 'time' => $begin];
  }

  public static function private_update_serial(model_request $request){
    $number = di::get('em')->find('data_number', $request->GET('number_id'));
    $meter = (new model_number2meter(di::get('company'), $number))
      ->update_serial($request->GET('meter_id'),
      $request->GET('serial'), $request->GET('new_serial'));
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_update_meter_comment(model_request $request){
    $number = di::get('em')->find('data_number', $request->GET('number_id'));
    $meter = (new model_number2meter(di::get('company'), $number))
      ->update_comment($request->GET('meter_id'),
      $request->GET('serial'), $request->GET('comment'));
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_update_period(model_request $request){
    $number = di::get('em')->find('data_number', $request->GET('number_id'));
    $meter = (new model_number2meter(di::get('company'), $number))
      ->update_period($request->GET('meter_id'), $request->GET('serial'),
      $request->GET('period'));
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_update_meter_status(model_request $request){
    $company = di::get('company');
    $number = di::get('em')->find($request->GET('number_id'));
    $model = new model_number2meter($company, $number);
    $model->update_status($request->GET('meter_id'), $request->GET('serial'));
    $model->init_meters();
    $enable_meters = $disable_meters = [];
    if(!empty($number->get_meters()))
        foreach($number->get_meters() as $meter)
            if($meter->get_status() == 'enabled')
                $enable_meters[] = $meter;
            elseif($meter->get_status() == 'disabled')
                $disable_meters[] = $meter;
    return ['number' => $number,
            'enable_meters' => $enable_meters,
            'disable_meters' => $disable_meters];
  }

  public static function private_update_meter_place(model_request $request){
    $number = di::get('em')->find('data_nmber', $request->GET('number_id'));
    $meter = (new model_number2meter(di::get('company'), $number))
      ->update_place($request->GET('meter_id'), $request->GET('serial'),
      $request->GET('place'));
    return ['number' => $number, 'meter' => $meter];
  }
}