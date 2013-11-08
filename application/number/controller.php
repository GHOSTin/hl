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
    $number = new data_number($request->GET('number_id'));
    (new model_number2meter(model_session::get_company(), $number))
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
    $house = new data_house($request->GET('house_id'));
    return ['house' => (new model_house2processing_center(
      model_session::get_company(), $house))
      ->add_processing_center($request->GET('center_id'),
      $request->GET('identifier'))];
  }

  public static function private_remove_house_processing_center(
    model_request $request){
    $house = new data_house($request->GET('house_id'));
    return ['house' => (new model_house2processing_center(
      model_session::get_company(), $house))
      ->remove_processing_center($request->GET('center_id'))];
  }

  public static function private_add_processing_center(model_request $request){
    $number = new data_number();
    $number->set_id($request->GET('number_id'));
    (new model_number2processing_center(model_session::get_company(), $number))
      ->add_processing_center($request->GET('center_id'),
        $request->GET('identifier'));
    return ['number' => $number];
  }

  public static function private_change_meter(model_request $request){
    $company = model_session::get_company();
    $number = (new model_number($company))
      ->get_number($request->GET('number_id'));
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
    $number = new data_number($request->GET('number_id'));
    (new model_number2meter(model_session::get_company(), $number))
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
    $number = new data_number($request->GET('number_id'));
    (new model_number2processing_center(model_session::get_company(), $number))
      ->delete_processing_center($request->GET('center_id'));
    return ['number' => $number];
  }

	public static function private_show_default_page(model_request $request){
    return ['streets' => (new model_street)->get_streets()];
	}

  public static function private_get_street_content(model_request $request){
    $street = new data_street();
    $street->set_id($request->take_get('id'));
    (new model_street2house($street))->init_houses();
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
    $house = new data_house($request->GET('house_id'));
    (new mapper_house2processing_center(
      model_session::get_company(), $house))->init_processing_centers();
    return ['house' => $house];
  }

  public static function private_get_dialog_add_meter_option(
    model_request $request){
    $time = getdate();
    return ['meter' => (new model_meter(model_session::get_company()))
      ->get_meter($request->GET('meter_id')),
      'time' => $time['mday'].'.'.$time['mon'].'.'.$time['year']];
  }

  public static function private_get_dialog_add_processing_center(
    model_request $request){
    return ['centers' => (new model_processing_center)
      ->get_processing_centers()];
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
    $number = new data_number($request->GET('id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
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
    $house = (new model_house)->get_house($request->take_get('id'));
    (new model_house2number(model_session::get_company(), $house))
      ->init_numbers();
    return ['house' => $house];
  }

  public static function private_get_house_information(model_request $request){
    $house = (new model_house)->get_house($request->GET('id'));
    (new model_house2processing_center(
      model_session::get_company(), $house))->init_processing_centers();
    return ['house' => $house];
  }

  public static function private_get_house_numbers(model_request $request){
    $house = (new model_house)->get_house($request->GET('id'));
    (new model_house2number(model_session::get_company(), $house))
      ->init_numbers();
    return ['house' => $house];
  }

  public static function private_get_meters(model_request $request){
    $company = model_session::get_company();
    $number = new data_number();
    $number->set_id($request->GET('id'));
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
    $model = new model_number($company);
    return ['number' => $model->get_number($request->GET('id')),
            'enable_meters' => $enable_meters,
            'disable_meters' => $disable_meters];
  }

  public static function private_get_number_content(model_request $request){
      $company = model_session::get_company();
      $number = (new model_number(model_session::get_company()))
                ->get_number($request->GET('id'));
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
      $model = new model_number(model_session::get_company());
      return ['number' => $model->get_number($request->GET('id'))];
  }

  public static function private_get_meter_data(model_request $request){
    $company = model_session::get_company();
    $number = new data_number($request->GET('id'));
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
    return ['meter' => (new model_meter(model_session::get_company()))
      ->get_meter($request->GET('meter_id'))];
  }

  public static function private_get_meter_value(model_request $request){
    return self::private_get_meter_data($request);
  }

  public static function private_get_meter_cart(model_request $request){
    $company = model_session::get_company();
    $number = (new model_number($company))
      ->get_number($request->GET('number_id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_get_meter_docs(model_request $request){
    return true;
  }

  public static function private_get_meter_info(model_request $request){
    $number = new data_number($request->GET('id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['number' => $number, 'meter' => $meter];
  }
    
  public static function private_get_meter_options(model_request $request){
    return ['meters' => (new model_meter(model_session::get_company()))
    ->get_meters_by_service($request->GET('service'))];
  }

  public static function private_get_processing_centers(model_request $request){
    $number = new data_number();
    $number->set_id($request->GET('id'));
    (new model_number2processing_center(model_session::get_company(), $number))
      ->init_processing_centers();
    self::set_param('number_content', 'processing_centers');
    return ['number' => $number];
  }

  public static function private_get_dialog_edit_number(model_request $request){
    return self::data_for_dialog_number($request);
  }

  private static function data_for_dialog_number(model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
                        ->get_number($request->GET('id'))];
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

  public static function private_get_dialog_edit_meter_data(
    model_request $request){
    $company = model_session::get_company();
    $number = new data_number($request->GET('id'));
    $n2m = (new model_number2meter($company, $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['n2m' => $n2m,
            'time' => $request->GET('time'),
            'meter_data' => (new model_meter2data($company, $n2m))
              ->get_value($request->GET('time')),
            'last_data' => null];
  }

  public static function private_update_date_checking(model_request $request){
    $time = explode('.', $request->GET('date'));
    $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
    $number = new data_number($request->GET('number_id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->update_date_checking($request->GET('meter_id'),
      $request->GET('serial'), $time);
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_update_date_install(model_request $request){
    $time = explode('.', $request->GET('date'));
    $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
    $number = new data_number($request->GET('number_id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->update_date_install($request->GET('meter_id'),
      $request->GET('serial'), $time);
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_update_date_release(model_request $request){
    $time = explode('.', $request->GET('date'));
    $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
    $number = new data_number($request->GET('number_id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->update_date_release($request->GET('meter_id'),
      $request->GET('serial'), $time);
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_update_number(model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
                ->update_number($request->GET('id'), $request->GET('number'))];
  }

  public static function private_update_number_password(model_request $request){
    if($request->GET('password') !== $request->GET('confirm'))
      throw new e_model('Подтверждение и пароль не совпадают.');
    return ['number' => (new model_number(model_session::get_company()))
            ->update_password($request->GET('id'), $request->GET('password'))];
  }

  public static function private_update_number_fio(model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
    ->update_number_fio($request->GET('id'), $request->GET('fio'))];
  }

  public static function private_update_number_cellphone(
    model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
    ->update_number_cellphone($request->GET('id'), $request->GET('cellphone'))];
  }

  public static function private_update_number_telephone(model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
    ->update_number_telephone($request->GET('id'), $request->GET('telephone'))];
  }

  public static function private_update_meter_data(model_request $request){
    $timestamp = explode('.', $request->GET('timestamp'));
    $timestamp = mktime(12, 0, 0, (int) $timestamp[1], (int) $timestamp[0], (int) $timestamp[2]);
    $company = model_session::get_company();
    $number = new data_number($request->GET('id'));
    $n2m = (new model_number2meter($company, $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    (new model_meter2data($company, $n2m))
      ->update_value($request->GET('time'), $request->GET('tarif'), 
        $request->GET('way'), $request->GET('comment'), $timestamp);
    if($request->GET('time') > 0)
        $time = getdate($request->GET('time'));
    else
        $time = getdate();
    $begin = mktime(12, 0, 0, 1, 1, $time['year']);
    $end = mktime(12, 0, 0, 12, 1, $time['year']);

    (new model_meter2data($company, $n2m))->init_values($begin, $end);
    return ['n2m' => $n2m, 'time' => $begin];
  }

  public static function private_update_serial(model_request $request){
    $number = new data_number($request->GET('number_id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->update_serial($request->GET('meter_id'),
      $request->GET('serial'), $request->GET('new_serial'));
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_update_meter_comment(model_request $request){
    $number = new data_number($request->GET('number_id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->update_comment($request->GET('meter_id'),
      $request->GET('serial'), $request->GET('comment'));
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_update_period(model_request $request){
    $period = ((int) $request->GET('year') * 12) + (int) $request->GET('month');
    $number = new data_number($request->GET('number_id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->update_period($request->GET('meter_id'), $request->GET('serial'),
      $period);
    return ['number' => $number, 'meter' => $meter];
  }

  public static function private_update_meter_status(model_request $request){
    $company = model_session::get_company();
    $number = new data_number($request->GET('number_id'));
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
    $model = new model_number($company);
    return ['number' => $number,
            'enable_meters' => $enable_meters,
            'disable_meters' => $disable_meters];
  }

  public static function private_update_meter_place(model_request $request){
    $number = new data_number($request->GET('number_id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->update_place($request->GET('meter_id'), $request->GET('serial'),
      $request->GET('place'));
    return ['number' => $number, 'meter' => $meter];
  }
}