<?php
class controller_number{

	static $name = 'Жилищный фонд';

    public static function private_add_meter(){
        $date_release = explode('.', $_GET['date_release']);
        $date_install = explode('.', $_GET['date_install']);
        $date_checking = explode('.', $_GET['date_checking']);
        $data = new data_number2meter();
        $data->number_id = $_GET['number_id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->service = $_GET['service'];
        $data->period = $_GET['period'];
        $data->place = $_GET['place'];
        $data->date_release = mktime(0, 0, 0, $date_release[1], $date_release[0], $date_release[2]);
        $data->date_install = mktime(0, 0, 0, $date_install[1], $date_install[0], $date_install[2]);
        $data->date_checking = mktime(0, 0, 0, $date_checking[1], $date_checking[0], $date_checking[2]);
        $company = model_session::get_company();
        model_number::add_meter($company, $data);
        $number2meter = new data_number2meter();
        $number2meter->number_id = $_GET['number_id'];
        $number2meter->meter_id = $_GET['meter_id'];
        return ['data' => $data,
                'meters' => model_number2meter::get_number2meters($company, $number2meter)];
    }

	public static function private_show_default_page(){
        return ['streets' => model_street::get_streets(new data_street())];
	}

    public static function private_get_street_content(){
        $street = new data_street();
        $street->id = $_GET['id'];
        model_street::verify_id($street);
        return ['houses' => model_street::get_houses($street),
                'street' => $street];
    }

    public static function private_get_dialog_add_meter(){
        $number = new data_number();
        $number->id = $_GET['id'];
        $number->verify('id');
        return ['numbers' => model_number::get_numbers(model_session::get_company(), $number)];
    }

    public static function private_get_dialog_add_meter_option(){
        $number = new data_number();
        $number->id = $_GET['number_id'];
        $number->verify('id');
        $meter = new data_meter();
        $meter->id = $_GET['meter_id'];
        $meter->service[] = $_GET['service'];
        $meter->verify('id', 'service');
        $time = getdate();
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter),
                'service' => $meter->service[0],
                'number' => $number,
                'time' => $time['mday'].'.'.$time['mon'].'.'.$time['year']];
    }


    public static function private_get_dialog_edit_serial(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
    }

    public static function private_get_house_content(){
        $house = new data_house();
        $house->id = $_GET['id'];
        model_house::verify_id($house);
        return ['numbers' => model_house::get_numbers(model_session::get_company(), $house),
                'house' => $house];
    }

    public static function private_get_meters(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->verify('number_id');
        $number = new data_number();
        $number->id = $_GET['id'];
        $number->verify('id');
        $company = model_session::get_company();
        return ['numbers' => model_number::get_numbers($company, $number),
                'meters' => model_number2meter::get_number2meters($company, $data)];
    }

    public static function private_get_number_content(){
        $number = new data_number();
        $number->id = $_GET['id'];
        $number->verify('id');
        return ['numbers' => model_number::get_numbers(model_session::get_company(), $number)];
    }

    public static function private_get_number_information(){
        $number = new data_number();
        $number->id = $_GET['id'];
        $number->verify('id');
        return ['numbers' => model_number::get_numbers(model_session::get_company(),$number)];
    }

    public static function private_get_meter_data(){
        $data = new data_number2meter();
        $data->meter_id = $_GET['meter_id'];
        $data->number_id = $_GET['id'];
        $data->serial = $_GET['serial'];
        $data->verify('meter_id', 'number_id', 'serial');
        if($_GET['time'] > 0)
            $time = getdate($_GET['time']);
        else
            $time = getdate();
        $time_begin = mktime(12, 0, 0, 1, 1, $time['year']);
        $time_end = mktime(12, 0, 0, 12, 1, $time['year']);
        $company = model_session::get_company();
        return ['meters' => model_number2meter::get_number2meters($company, $data), 
                'time' => $time_begin, 'meter_data' => model_number::get_meter_data($company, $data, $time_begin, $time_end)];
    }

    public static function private_get_meter_value(){
        $data = new data_number2meter();
        $data->meter_id = $_GET['meter_id'];
        $data->number_id = $_GET['id'];
        $data->serial = $_GET['serial'];
        $data->verify('meter_id', 'number_id', 'serial');
        if($_GET['time'] > 0)
            $time = getdate($_GET['time']);
        else
            $time = getdate();
        $time_begin = mktime(12, 0, 0, 1, 1, $time['year']);
        $time_end = mktime(12, 0, 0, 12, 1, $time['year']);
        $company = model_session::get_company();
        return ['meters' => model_number2meter::get_number2meters($company, $data), 
                'time' => $time_begin, 'meter_data' => model_number::get_meter_data($company, $data, $time_begin, $time_end)];
    }

    public static function private_get_meter_info(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
    }
    
    public static function private_get_meter_options(){
        $meter = new data_meter();
        $meter->service[] = $_GET['service'];
        $meter->verify('service');
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter)];
    }

    public static function private_get_dialog_edit_number(){
        $number = new data_number();
        $number->id = $_GET['id'];
        $number->verify('id');
        return ['numbers' => model_number::get_numbers(model_session::get_company(), $number)];
    }

    public static function private_get_dialog_edit_meter_data(){
        $time = $_GET['time'];
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        $company = model_session::get_company();
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data),
                'time' => $_GET['time']];
                // 'last_data' => model_number::get_last_meter_data($company, $number, $meter, $time)];
    }

    public static function private_update_number(){
        $number = new data_number();
        $number->id = $_GET['id'];
        $number->number = $_GET['number'];
        $number->verify('id', 'number');
        return model_number::update_number(model_session::get_company(), $number);
    }

    public static function private_update_meter_data(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        $meter2data = new data_meter2data();
        $meter2data->time = $_GET['time'];
        $meter2data->value = $_GET['tarif'];
        model_number::update_meter_data(model_session::get_company(), $data, $meter2data);
        $time = getdate();
        $time_begin = mktime(12, 0, 0, 1, 1, $time['year']);
        $time_end = mktime(12, 0, 0, 12, 1, $time['year']);
        $company = model_session::get_company();
        return ['meters' => model_number2meter::get_number2meters($company, $data), 
                'time' => $time_begin, 'meter_data' => model_number::get_meter_data($company, $data, $time_begin, $time_end)];
    }

    public static function private_update_serial(){
        $old_data = new data_number2meter();
        $old_data->number_id = $_GET['number_id'];
        $old_data->meter_id = $_GET['meter_id'];
        $old_data->serial = $_GET['serial'];
        $new_data = new data_number2meter();
        $new_data->serial = $_GET['new_serial'];
        $new_data->number_id = $_GET['number_id'];
        $new_data->meter_id = $_GET['meter_id'];
        $company = model_session::get_company();
        model_number::update_serial($company, $old_data, $new_data);
        return ['old_meter' => $old_data,
                'new_meters' => model_number2meter::get_number2meters($company, $new_data)];
    }
}