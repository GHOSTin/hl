<?php
class controller_number{

	static $name = 'Жилищный фонд';

    public static function private_add_meter(model_request $request){
        $date_release = explode('.', $_GET['date_release']);
        $date_install = explode('.', $_GET['date_install']);
        $date_checking = explode('.', $_GET['date_checking']);

        $number = new data_number($request->GET('number_id'));
        (new model_number2meter(model_session::get_company(), $number))
          ->add_meter($_GET['meter_id'], $_GET['serial'], $_GET['service'], $_GET['place'],
            mktime(12, 0, 0, $date_release[1], $date_release[0], $date_release[2]),
            mktime(12, 0, 0, $date_install[1], $date_install[0], $date_install[2]),
            mktime(12, 0, 0, $date_checking[1], $date_checking[0], $date_checking[2]),
            $_GET['period'], $_GET['comment']);

        exit();
        $meters = $model->get_meters();
        $enable_meters = $disable_meters = [];
        if(!empty($meters))
            foreach($meters as $meter)
                if($meter->get_status() == 'enabled')
                    $enable_meters[] = $meter;
                elseif($meter->get_status() == 'disabled')
                    $disable_meters[] = $meter;
        return ['number_id' => $_GET['number_id'],
                'enable_meters' => $enable_meters, 'disable_meters' => $disable_meters];
    }

    public static function private_add_house_processing_center(model_request $request){
        $model = new model_house();
        $house = $model->add_processing_center(model_session::get_company(), 
            $_GET['house_id'], $_GET['center_id'], $_GET['identifier']);
        return ['house' => $house];
    }

    public static function private_remove_house_processing_center(model_request $request){
        $model = new model_house();
        $house = $model->remove_processing_center(model_session::get_company(), 
                                        $_GET['house_id'], $_GET['center_id']);
        return ['house' => $house];
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
        $model = new model_number2meter(model_session::get_company(), $_GET['number_id']);
        $model->change_meter($_GET['meter_id'], $_GET['serial'], $_GET['new_meter_id'],
            $_GET['new_serial'], $_GET['service'], $_GET['place'],
            mktime(12, 0, 0, $date_release[1], $date_release[0], $date_release[2]),
            mktime(12, 0, 0, $date_install[1], $date_install[0], $date_install[2]),
            mktime(12, 0, 0, $date_checking[1], $date_checking[0], $date_checking[2]),
            $_GET['period'], $_GET['comment']);
        return ['number_id' => $_GET['number_id'],
                'meters' => $model->get_meters()];
    }

    public static function private_delete_meter(model_request $request){
        $company = model_session::get_company();
        $model = new model_number2meter($company, $_GET['number_id']);
        $model->remove_meter($_GET['meter_id'], $_GET['serial']);
        $meters = $model->get_meters();
        $model = new model_number($company);
        return ['number' => $model->get_number($_GET['number_id']),
                'meters' => $meters];
    }

  public static function private_exclude_processing_center(model_request $request){
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

    public static function private_get_dialog_add_house_processing_center(model_request $request){
        $house = new data_house();
        $house->id = $_GET['id'];
        $house->verify('id');
        return ['centers' => model_processing_center::get_processing_centers(new data_processing_center()),
                'house' => $house];
    }

    public static function private_get_dialog_remove_house_processing_center(model_request $request){
        $house = new data_house();
        $house->id = $_GET['house_id'];
        $house->verify('id');
        $house = model_house::get_houses($house)[0];
        $mapper = new mapper_house2processing_center(model_session::get_company(), $house);
        $mapper->init_processing_centers();
        return ['house' => $house, 'center_id' => $_GET['center_id']];
    }

  public static function private_get_dialog_add_meter_option(model_request $request){
    $time = getdate();
    return ['meter' => (new model_meter(model_session::get_company()))
      ->get_meter($request->GET('meter_id')),
      'time' => $time['mday'].'.'.$time['mon'].'.'.$time['year']];
  }

  public static function private_get_dialog_add_processing_center(model_request $request){
    return ['centers' => (new model_processing_center)->get_processing_centers()];
  }

    public static function private_get_dialog_change_meter(model_request $request){
        $data = new data_number2meter();
        $data->set_number_id($_GET['id']);
        $data->set_meter_id($_GET['meter_id']);
        $data->set_serial($_GET['serial']);
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meter' => $data];
    }

    public static function private_get_dialog_change_meter_option(model_request $request){
        $company = model_session::get_company();
        $model = new model_meter($company);
        $meter = $model->get_meter($_GET['new_meter_id']);
        $model = new model_number2meter($company, $_GET['number_id']);
        return ['meter' => $meter,
                'service' => $_GET['service'],
                'old_meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_dialog_delete_meter(model_request $request){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_dialog_edit_date_checking(model_request $request){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

  public static function private_get_dialog_edit_date_install(model_request $request){
    $number = new data_number($request->GET('id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['n2m' => $meter];
  }

  public static function private_get_dialog_edit_date_release(model_request $request){
    $number = new data_number($request->GET('id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['n2m' => $meter];
  }

    public static function private_get_dialog_edit_meter_comment(model_request $request){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_dialog_edit_period(model_request $request){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

  public static function private_get_dialog_edit_meter_status(model_request $request){
    $number = new data_number($request->GET('id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['n2m' => $meter];
  }

  public static function private_get_dialog_edit_meter_place(model_request $request){
    $number = new data_number($request->GET('id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['n2m' => $meter];
  }

  public static function private_get_dialog_edit_serial(model_request $request){
    $number = new data_number($request->GET('id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['n2m' => $meter];
  }

    public static function private_get_dialog_exclude_processing_center(model_request $request){
      return ['center' => (new model_processing_center)
                          ->get_processing_center($request->GET('center_id'))];
    }

    public static function private_get_house_content(model_request $request){
      $house = new data_house();
      $house->set_id($request->take_get('id'));
      (new model_house2number(model_session::get_company(), $house))->init_numbers();
      return ['house' => $house];
    }

    public static function private_get_house_information(model_request $request){
      $house = (new model_house)->get_house($request->GET('id'));
      $model = new model_house2processing_center(model_session::get_company(), $house);
      $model->init_processing_centers();
      return ['house' => $house];
    }

    public static function private_get_house_numbers(model_request $request){
      $house = new data_house();
      $house->set_id($request->GET('id'));
      (new model_house2number(model_session::get_company(), $house))->init_numbers();
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
        model_session::set_setting_param('number', 'number_content', 'meters');
        $model = new model_number($company);
        return ['number' => $model->get_number($_GET['id']),
                'enable_meters' => $enable_meters, 'disable_meters' => $disable_meters];
    }

    public static function private_get_number_content(model_request $request){
        $switch = model_session::get_setting_param('number', 'number_content');
        $company = model_session::get_company();
        $number = (new model_number(model_session::get_company()))
                  ->get_number($request->GET('id'));
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
            model_session::set_setting_param('number', 'number_content', 'meters');
            return ['number' => $number,
              'enable_meters' => $enable_meters, 'disable_meters' => $disable_meters,
              'setting' => $switch];
          break;

          case 'centers':
              (new model_number2processing_center($company, $number))->init_processing_centers();
              return ['number' => $number, 'setting' => $switch];
          break;

          default:
            return ['number' => $number];
        }
    }

    public static function private_get_number_information(model_request $request){
        model_session::set_setting_param('number', 'number_content', 'information');
        $model = new model_number(model_session::get_company());
        return ['number' => $model->get_number($request->GET('id'))];
    }

    public static function private_get_meter_data(model_request $request){
        $company = model_session::get_company();
        $number = new data_number($request->GET('id'));
        $meter = (new model_number2meter($company, $number))
            ->get_meter($request->GET('meter_id'), $request->GET('serial'));
        if($_GET['time'] > 0)
            $time = getdate($_GET['time']);
        else
            $time = getdate();
        $begin = mktime(12, 0, 0, 1, 1, $time['year']);
        $end = mktime(12, 0, 0, 12, 1, $time['year']);

        (new model_meter2data($company, $meter))->init_values($begin, $end);
        return ['n2m' => $meter, 'time' => $begin];
    }

    public static function private_get_meter_value(model_request $request){
        $company = model_session::get_company();
        $model = new model_number2meter($company, $_GET['id']);
        $meter = $model->get_meter($_GET['meter_id'], $_GET['serial']);
        if($_GET['time'] > 0)
            $time = getdate($_GET['time']);
        else
            $time = getdate();
        $begin = mktime(12, 0, 0, 1, 1, $time['year']);
        $end = mktime(12, 0, 0, 12, 1, $time['year']);
        $model = new model_meter2data($company, $_GET['id'], $_GET['meter_id'], $_GET['serial']);
        return ['meter' => $meter, 
                'time' => $begin, 'meter_data' => $model->get_values($begin, $end)];
    }

    public static function private_get_meter_cart(model_request $request){
        // $time = getdate();
        // $time_begin = mktime(12, 0, 0, 1, 1, $time['year']);
        // $time_end = mktime(12, 0, 0, 12, 1, $time['year']);
      $company = model_session::get_company();
      $number = (new model_number($company))->get_number($request->GET('number_id'));
      $meter = (new model_number2meter(model_session::get_company(), $number))
        ->get_meter($request->GET('meter_id'), $request->GET('serial'));
      return ['n2m' => $meter];
        // return ['meter' => $meter,
        //         'number' => $number, 'time' => $time_begin,
        //         'meter_data' => $model->get_values($time_begin, $time_end)];
    }

  public static function private_get_meter_docs(model_request $request){
    $number = new data_number($request->GET('id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['n2m' => $meter];
  }

  public static function private_get_meter_info(model_request $request){
    $number = new data_number($request->GET('id'));
    $meter = (new model_number2meter(model_session::get_company(), $number))
      ->get_meter($request->GET('meter_id'), $request->GET('serial'));
    return ['n2m' => $meter];
  }
    
  public static function private_get_meter_options(model_request $request){
    return ['meters' => (new model_meter(model_session::get_company()))
    ->get_meters_by_service($request->GET('service'))];
  }

    public static function private_get_processing_centers(model_request $request){
      $number = new data_number();
      $number->set_id($request->GET('id'));
      (new model_number2processing_center(model_session::get_company(), $number))->init_processing_centers();
      model_session::set_setting_param('number', 'number_content', 'centers');
      return ['number' => $number];
    }

  public static function private_get_dialog_edit_number(model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
                        ->get_number($request->GET('id'))];
  }

  public static function private_get_dialog_edit_number_fio(model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
                        ->get_number($request->GET('id'))];;
  }

  public static function private_get_dialog_edit_number_telephone(model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
                        ->get_number($request->GET('id'))];
  }

  public static function private_get_dialog_edit_number_cellphone(model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
                        ->get_number($request->GET('id'))];
  }

    public static function private_get_dialog_edit_meter_data(model_request $request){
        $time = getdate($_GET['time']);
        $company = model_session::get_company();
        $model = new model_number2meter($company, $_GET['id']);
        $meter = $model->get_meter($_GET['meter_id'], $_GET['serial']);
        //$model = model_meter2data($company, $_GET['id'], $_GET['meter_id'], $_GET['serial']);
        return ['meter' => $meter,
            'time' => $_GET['time'],
            'current_meter_data' => null,
            'last_data' => null];
    }

    public static function private_update_date_checking(model_request $request){
        $time = explode('.', $_GET['date']);
        $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
        $model = new model_number2meter(model_session::get_company(), $_GET['number_id']);
        return ['meter' => $model->update_date_checking($_GET['meter_id'], $_GET['serial'], $time)];
    }

  public static function private_update_date_install(model_request $request){
    $time = explode('.', $request->GET('date'));
    $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
    $number = new data_number($request->GET('number_id'));
    return ['n2m' => (new model_number2meter(model_session::get_company(), $number))
      ->update_date_install($request->GET('meter_id'), $request->GET('serial'),
        $time)];
  }

  public static function private_update_date_release(model_request $request){
    $time = explode('.', $request->GET('date'));
    $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
    $number = new data_number($request->GET('number_id'));
    return ['n2m' => (new model_number2meter(model_session::get_company(), $number))
      ->update_date_release($request->GET('meter_id'), $request->GET('serial'),
        $time)];
  }

  public static function private_update_number(model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
                ->update_number($request->GET('id'), $request->GET('number'))];
  }

  public static function private_update_number_fio(model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
    ->update_number_fio($request->GET('id'), $request->GET('fio'))];
  }

  public static function private_update_number_cellphone(model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
    ->update_number_cellphone($request->GET('id'), $request->GET('cellphone'))];
  }

  public static function private_update_number_telephone(model_request $request){
    return ['number' => (new model_number(model_session::get_company()))
    ->update_number_telephone($request->GET('id'), $request->GET('telephone'))];
  }

    public static function private_update_meter_data(model_request $request){
        $timestamp = explode('.', $_GET['timestamp']);
        $timestamp = mktime(12, 0, 0, (int) $timestamp[1], (int) $timestamp[0], (int) $timestamp[2]);
        $time = getdate($_GET['time']);
        $time_begin = mktime(12, 0, 0, 1, 1, $time['year']);
        $time_end = mktime(12, 0, 0, 12, 1, $time['year']);
        $model = new model_meter2data(model_session::get_company(), $_GET['id'],
                                        $_GET['meter_id'], $_GET['serial']);
        $meter = $model->update_value($_GET['time'], $_GET['tarif'], $_GET['way'], $_GET['comment'], $timestamp);
        return ['meter' => $meter, 'time' => $time_begin,
                'meter_data' => $model->get_values($time_begin, $time_end)];
    }

  public static function private_update_serial(model_request $request){
    $number = new data_number($request->GET('number_id'));
    return ['n2m' => (new model_number2meter(model_session::get_company(), $number))
      ->update_serial($request->GET('meter_id'), $request->GET('serial'),
        $request->GET('new_serial'))];
  }

    public static function private_update_meter_comment(model_request $request){
        $model = new model_number2meter(model_session::get_company(), $_GET['number_id']);
        return ['meter' => $model->update_comment($_GET['meter_id'], $_GET['serial'], $_GET['comment'])];
    }

    public static function private_update_period(model_request $request){
        $period = ((int) $_GET['year']*12) + (int) $_GET['month'];
        $model = new model_number2meter(model_session::get_company(), $_GET['number_id']);
        return ['meter' => $model->update_period($_GET['meter_id'], $_GET['serial'], $period)];
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
            'enable_meters' => $enable_meters, 'disable_meters' => $disable_meters];
  }

  public static function private_update_meter_place(model_request $request){
    $number = new data_number($request->GET('number_id'));
    return ['n2m' => (new model_number2meter(model_session::get_company(), $number))
      ->update_place($request->GET('meter_id'), $request->GET('serial'),
        $request->GET('place'))];
  }
}