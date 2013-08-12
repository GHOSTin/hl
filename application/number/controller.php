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
        $data->comment = $_GET['comment'];
        $data->date_release = mktime(0, 0, 0, $date_release[1], $date_release[0], $date_release[2]);
        $data->date_install = mktime(0, 0, 0, $date_install[1], $date_install[0], $date_install[2]);
        $data->date_checking = mktime(0, 0, 0, $date_checking[1], $date_checking[0], $date_checking[2]);
        $company = model_session::get_company();
        model_number::add_meter($company, $data);
        $number2meter = new data_number2meter();
        $number2meter->number_id = $_GET['number_id'];
        $meters = model_number2meter::get_number2meters($company, $number2meter);
        $enable_meters = $disable_meters = [];
        if(!empty($meters))
            foreach($meters as $meter)
                if($meter->status == 'enabled')
                    $enable_meters[] = $meter;
                elseif($meter->status == 'disabled')
                    $disable_meters[] = $meter;
        return ['data' => $data,
                'enable_meters' => $enable_meters, 'disable_meters' => $disable_meters];
    }

    public static function private_add_processing_center(){
        $center = new data_processing_center2number();
        $center->number_id = $_GET['number_id'];
        $center->processing_center_id = $_GET['center_id'];
        $center->identifier = $_GET['identifier'];
        $company = model_session::get_company();
        model_processing_center2number::add_identifier($company, $center);
        $c2n = new data_processing_center2number();
        $c2n->number_id = $_GET['number_id'];
        $c2n->verify('number_id');
        return ['centers' => model_processing_center2number::get_processing_centers($company, $c2n),
                'data' => $c2n];
    }

    public static function private_change_meter(){
        $old = new data_number2meter();
        $old->number_id = $_GET['number_id'];
        $old->meter_id = $_GET['meter_id'];
        $old->serial = $_GET['serial'];
        $date_release = explode('.', $_GET['date_release']);
        $date_install = explode('.', $_GET['date_install']);
        $date_checking = explode('.', $_GET['date_checking']);
        $new = new data_number2meter();
        $new->number_id = $_GET['number_id'];
        $new->meter_id = $_GET['new_meter_id'];
        $new->serial = $_GET['new_serial'];
        $new->service = $_GET['service'];
        $new->period = $_GET['period'];
        $new->place = $_GET['place'];
        $new->comment = $_GET['comment'];
        $new->date_release = mktime(0, 0, 0, $date_release[1], $date_release[0], $date_release[2]);
        $new->date_install = mktime(0, 0, 0, $date_install[1], $date_install[0], $date_install[2]);
        $new->date_checking = mktime(0, 0, 0, $date_checking[1], $date_checking[0], $date_checking[2]);
        $company = model_session::get_company();
        model_number::change_meter($company, $old, $new);
        $per = new data_number2meter();
        $per->number_id = $old->number_id;
        return ['old_meter' => $old,
                'meters' => model_number2meter::get_number2meters($company, $per)];
    }

    public static function private_delete_meter(){
        $meter = new data_number2meter();
        $meter->number_id = $_GET['number_id'];
        $meter->meter_id = $_GET['meter_id'];
        $meter->serial = $_GET['serial'];
        $company = model_session::get_company();
        model_number::delete_meter($company, $meter);
        $per = new data_number2meter();
        $per->number_id = $_GET['number_id'];
        return ['meter' => $meter,
                'meters' => model_number2meter::get_number2meters($company, $per)];
    }

    public static function private_exclude_processing_center(){
        $center = new data_processing_center2number();
        $center->number_id = $_GET['number_id'];
        $center->processing_center_id = $_GET['center_id'];
        $center->identifier = $_GET['identifier'];
        $company = model_session::get_company();
        model_processing_center2number::exclude_identifier($company, $center);
        $c2n = new data_processing_center2number();
        $c2n->number_id = $_GET['number_id'];
        $c2n->verify('number_id');
        return ['centers' => model_processing_center2number::get_processing_centers($company, $c2n),
                'data' => $c2n];
    }

	public static function private_show_default_page(){
        return ['streets' => model_street::get_streets(new data_street())];
	}

    public static function private_get_street_content(){
        $street = new data_street();
        $street->id = $_GET['id'];
        $street->verify('id');
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

    public static function private_get_dialog_add_processing_center(){
        $number = new data_number();
        $number->id = $_GET['id'];
        $number->verify('id');
        return ['centers' => model_processing_center::get_processing_centers(new data_processing_center()),
                'number' => $number];
    }

    public static function private_get_dialog_change_meter(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meter' => $data];
    }

    public static function private_get_dialog_change_meter_option(){
        $data = new data_number2meter();
        $data->number_id = $_GET['number_id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        $meter = new data_meter();
        $meter->id = $_GET['new_meter_id'];
        $meter->service[] = $_GET['service'];
        $meter->verify('id', 'service');
        $time = getdate();
        return ['meters' => model_meter::get_meters(model_session::get_company(), $meter),
                'service' => $meter->service[0],
                'old_meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
    }

    public static function private_get_dialog_delete_meter(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
    }

    public static function private_get_dialog_edit_date_checking(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
    }

    public static function private_get_dialog_edit_date_install(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
    }

    public static function private_get_dialog_edit_date_release(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
    }

    public static function private_get_dialog_edit_meter_comment(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
    }

    public static function private_get_dialog_edit_period(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
    }

    public static function private_get_dialog_edit_meter_status(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
    }

    public static function private_get_dialog_edit_meter_place(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
    }

    public static function private_get_dialog_edit_serial(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
    }

    public static function private_get_dialog_exclude_processing_center(){
        $center = new data_processing_center();
        $center->id = $_GET['center_id'];
        $center->verify('id');
        $number = new data_number();
        $number->id = $_GET['number_id'];
        return ['center' => model_processing_center::get_processing_centers($center)[0],
                'number' => $number,
                'identifier' => $_GET['identifier']];
    }

    public static function private_get_house_content(){
        $house = new data_house();
        $house->id = $_GET['id'];
        $house->verify('id');
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
        $meters = model_number2meter::get_number2meters($company, $data);
        $enable_meters = $disable_meters = [];
        if(!empty($meters))
            foreach($meters as $meter)
                if($meter->status == 'enabled')
                    $enable_meters[] = $meter;
                elseif($meter->status == 'disabled')
                    $disable_meters[] = $meter;
        model_session::set_setting_param('number', 'number_content', 'meters');
        return ['numbers' => model_number::get_numbers($company, $number),
                'enable_meters' => $enable_meters, 'disable_meters' => $disable_meters];
    }

    public static function private_get_number_content(){
        $number = new data_number();
        $number->id = $_GET['id'];
        $number->verify('id');
        $switch = model_session::get_setting_param('number', 'number_content');
        switch($switch){
            case 'meters':
                $data = new data_number2meter();
                $data->number_id = $_GET['id'];
                $data->verify('number_id');
                $number = new data_number();
                $number->id = $_GET['id'];
                $number->verify('id');
                $company = model_session::get_company();
                $meters = model_number2meter::get_number2meters($company, $data);
                $enable_meters = $disable_meters = [];
                if(!empty($meters))
                    foreach($meters as $meter)
                        if($meter->status == 'enabled')
                            $enable_meters[] = $meter;
                        elseif($meter->status == 'disabled')
                            $disable_meters[] = $meter;
                return ['numbers' => model_number::get_numbers($company, $number),
                        'enable_meters' => $enable_meters, 'disable_meters' => $disable_meters,
                        'setting' => $switch];
            break;

            case 'centers':
                $c2n = new data_processing_center2number();
                $c2n->number_id = $_GET['id'];
                $c2n->verify('number_id');
                $company = model_session::get_company();
                return ['centers' => model_processing_center2number::get_processing_centers($company, $c2n),
                        'numbers' => [$number],
                        'setting' => $switch];
            break;

            default:
                return ['numbers' => model_number::get_numbers(model_session::get_company(), $number)];
        }
    }

    public static function private_get_number_information(){
        $number = new data_number();
        $number->id = $_GET['id'];
        $number->verify('id');
        model_session::set_setting_param('number', 'number_content', 'information');
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

    public static function private_get_meter_cart(){
        $data = new data_number2meter();
        $data->number_id = $_GET['number_id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        $number = new data_number();
        $number->id = $_GET['number_id'];
        $number->verify('id');
        $company = model_session::get_company();
        $time = getdate();
        $time_begin = mktime(12, 0, 0, 1, 1, $time['year']);
        $time_end = mktime(12, 0, 0, 12, 1, $time['year']);
        return ['meters' => model_number2meter::get_number2meters($company, $data),
                'numbers' => model_number::get_numbers($company, $number), 'time' => $time_begin,
                'meter_data' => model_number::get_meter_data($company, $data, $time_begin, $time_end)];
    }

    public static function private_get_meter_docs(){
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data)];
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

    public static function private_get_processing_centers(){
        $c2n = new data_processing_center2number();
        $c2n->number_id = $_GET['id'];
        $c2n->verify('number_id');
        $company = model_session::get_company();
        model_session::set_setting_param('number', 'number_content', 'centers');
        return ['centers' => model_processing_center2number::get_processing_centers($company, $c2n),
                'data' => $c2n];
    }

    public static function private_get_dialog_edit_number(){
        $model = new model_number(model_session::get_company());
        return ['number' => $model->get_number($_GET['id'])];
    }

    public static function private_get_dialog_edit_number_fio(){
        $model = new model_number(model_session::get_company());
        return ['number' => $model->get_number($_GET['id'])];
    }

    public static function private_get_dialog_edit_number_telephone(){
        $model = new model_number(model_session::get_company());
        return ['number' => $model->get_number($_GET['id'])];
    }

    public static function private_get_dialog_edit_number_cellphone(){
        $model = new model_number(model_session::get_company());
        return ['number' => $model->get_number($_GET['id'])];
    }

    public static function private_get_dialog_edit_meter_data(){
        $time = $_GET['time'];
        $data = new data_number2meter();
        $data->number_id = $_GET['id'];
        $data->meter_id = $_GET['meter_id'];
        $data->serial = $_GET['serial'];
        $time = getdate($_GET['time']);
        $time_begin = mktime(12, 0, 0, $time['mon'], 1, $time['year']);
        $time_end = mktime(12, 0, 0, $time['mon'], 1, $time['year']);
        $data->verify('number_id', 'meter_id', 'serial');
        $company = model_session::get_company();
        return ['meters' => model_number2meter::get_number2meters(model_session::get_company(), $data),
                'time' => $_GET['time'],
                'current_meter_data' => model_number::get_meter_data($company, $data, $time_begin, $time_end),
                'last_data' => model_number::get_last_meter_data($company, $data, $time_begin)];
    }

    public static function private_update_date_checking(){
        $meter = new data_number2meter();
        $meter->number_id = $_GET['number_id'];
        $meter->meter_id = $_GET['meter_id'];
        $meter->serial = $_GET['serial'];
        $time = explode('.', $_GET['date']);
        $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
        $company = model_session::get_company();
        model_number::update_date_checking($company, $meter, $time);
        return ['meters' => model_number2meter::get_number2meters($company, $meter)];
    }

    public static function private_update_date_install(){
        $meter = new data_number2meter();
        $meter->number_id = $_GET['number_id'];
        $meter->meter_id = $_GET['meter_id'];
        $meter->serial = $_GET['serial'];
        $time = explode('.', $_GET['date']);
        $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
        $company = model_session::get_company();
        model_number::update_date_install($company, $meter, $time);
        return ['meters' => model_number2meter::get_number2meters($company, $meter)];
    }

    public static function private_update_date_release(){
        $meter = new data_number2meter();
        $meter->number_id = $_GET['number_id'];
        $meter->meter_id = $_GET['meter_id'];
        $meter->serial = $_GET['serial'];
        $time = explode('.', $_GET['date']);
        $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
        $company = model_session::get_company();
        model_number::update_date_release($company, $meter, $time);
        return ['meters' => model_number2meter::get_number2meters($company, $meter)];
    }

    public static function private_update_number(){
        $model = new model_number(model_session::get_company());
        return ['number' => $model->update_number($_GET['id'], $_GET['number'])];
    }

    public static function private_update_number_fio(){
        $model = new model_number(model_session::get_company());
        return ['number' => $model->update_number_fio($_GET['id'], $_GET['fio'])];
    }

    public static function private_update_number_cellphone(){
        $model = new model_number(model_session::get_company());
        return ['number' => $model->update_number_cellphone($_GET['id'], $_GET['cellphone'])];
    }

    public static function private_update_number_telephone(){
        $model = new model_number(model_session::get_company());
        return ['number' => $model->update_number_telephone($_GET['id'], $_GET['telephone'])];
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
        $meter2data->comment = $_GET['comment'];
        $timestamp = explode('.', $_GET['timestamp']);
        $meter2data->timestamp = mktime(12, 0, 0, $timestamp[1], $timestamp[0], $timestamp[2]);
        $meter2data->way = $_GET['way'];
        model_number::update_meter_data(model_session::get_company(), $data, $meter2data);
        $time = getdate();
        $time_begin = mktime(12, 0, 0, 1, 1, $time['year']);
        $time_end = mktime(12, 0, 0, 12, 1, $time['year']);
        $company = model_session::get_company();
        return ['meters' => model_number2meter::get_number2meters($company, $data), 
                'time' => $time_begin, 'meter_data' => model_number::get_meter_data($company, $data, $time_begin, $time_end)];
    }

    public static function private_update_serial(){
        $meter = new data_number2meter();
        $meter->number_id = $_GET['number_id'];
        $meter->meter_id = $_GET['meter_id'];
        $meter->serial = $_GET['serial'];
        $company = model_session::get_company();
        $new_meter = model_number::update_serial($company, $meter, $_GET['new_serial']);
        return ['old_meter' => $meter,
                'new_meters' => [$new_meter]];
    }

    public static function private_update_meter_comment(){
        $meter = new data_number2meter();
        $meter->number_id = $_GET['number_id'];
        $meter->meter_id = $_GET['meter_id'];
        $meter->serial = $_GET['serial'];
        $company = model_session::get_company();
        model_number::update_meter_comment($company, $meter, $_GET['comment']);
        return ['meters' => model_number2meter::get_number2meters($company, $meter)];
    }

    public static function private_update_period(){
        $meter = new data_number2meter();
        $meter->number_id = $_GET['number_id'];
        $meter->meter_id = $_GET['meter_id'];
        $meter->serial = $_GET['serial'];
        $company = model_session::get_company();
        $period = ((int) $_GET['year']*12) + (int) $_GET['month'];
        model_number::update_period($company, $meter, $period);
        return ['meters' => model_number2meter::get_number2meters($company, $meter)];
    }

    public static function private_update_meter_status(){
        $meter = new data_number2meter();
        $meter->number_id = $_GET['number_id'];
        $meter->meter_id = $_GET['meter_id'];
        $meter->serial = $_GET['serial'];
        $company = model_session::get_company();
        model_number::update_meter_status($company, $meter, $_GET['status']);
        $number2meter = new data_number2meter();
        $number2meter->number_id = $_GET['number_id'];
        $meters = model_number2meter::get_number2meters($company, $number2meter);
        $enable_meters = $disable_meters = [];
        if(!empty($meters))
            foreach($meters as $meter)
                if($meter->status == 'enabled')
                    $enable_meters[] = $meter;
                elseif($meter->status == 'disabled')
                    $disable_meters[] = $meter;
        return ['data' => $meter,
                'enable_meters' => $enable_meters, 'disable_meters' => $disable_meters];
    }

    public static function private_update_meter_place(){
        $meter = new data_number2meter();
        $meter->number_id = $_GET['number_id'];
        $meter->meter_id = $_GET['meter_id'];
        $meter->serial = $_GET['serial'];
        $company = model_session::get_company();
        model_number::update_meter_place($company, $meter, $_GET['place']);
        return ['meters' => model_number2meter::get_number2meters($company, $meter)];
    }
}