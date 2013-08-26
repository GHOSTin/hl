<?php
class controller_number{

	static $name = 'Жилищный фонд';

    public static function private_add_meter(){
        $date_release = explode('.', $_GET['date_release']);
        $date_install = explode('.', $_GET['date_install']);
        $date_checking = explode('.', $_GET['date_checking']);
        $company = model_session::get_company();
        $model = new model_number2meter($company, $_GET['number_id']);
        $model->add_meter($_GET['meter_id'], $_GET['serial'], $_GET['service'], $_GET['place'],
            mktime(12, 0, 0, $date_release[1], $date_release[0], $date_release[2]),
            mktime(12, 0, 0, $date_install[1], $date_install[0], $date_install[2]),
            mktime(12, 0, 0, $date_checking[1], $date_checking[0], $date_checking[2]),
            $_GET['period'], $_GET['comment']);
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

    public static function private_delete_meter(){
        $company = model_session::get_company();
        $model = new model_number2meter($company, $_GET['number_id']);
        $model->remove_meter($_GET['meter_id'], $_GET['serial']);
        $meters = $model->get_meters();
        $model = new model_number($company);
        return ['number' => $model->get_number($_GET['number_id']),
                'meters' => $meters];
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
        $model = new model_number(model_session::get_company());
        return ['number' => $model->get_number($_GET['id'])];
    }

    public static function private_get_dialog_add_meter_option(){
        $number = new data_number();
        $number->id = $_GET['number_id'];
        $number->verify('id');
        $model = new model_meter(model_session::get_company());
        $time = getdate();
        return ['meter' => $model->get_meter($_GET['meter_id']),
                'number' => $number, 'service' => $_GET['service'],
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
        $data->set_number_id($_GET['id']);
        $data->set_meter_id($_GET['meter_id']);
        $data->set_serial($_GET['serial']);
        $data->verify('number_id', 'meter_id', 'serial');
        return ['meter' => $data];
    }

    public static function private_get_dialog_change_meter_option(){
        $company = model_session::get_company();
        $model = new model_meter($company);
        $meter = $model->get_meter($_GET['new_meter_id']);
        $model = new model_number2meter($company, $_GET['number_id']);
        return ['meter' => $meter,
                'service' => $_GET['service'],
                'old_meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_dialog_delete_meter(){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_dialog_edit_date_checking(){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_dialog_edit_date_install(){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_dialog_edit_date_release(){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_dialog_edit_meter_comment(){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_dialog_edit_period(){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_dialog_edit_meter_status(){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_dialog_edit_meter_place(){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_dialog_edit_serial(){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
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

    public static function private_get_house_information(){
        $house = new data_house();
        $house->id = $_GET['id'];
        $house->verify('id');
        return ['house' => model_house::get_houses($house)[0]];
    }

    public static function private_get_house_numbers(){
        $house = new data_house();
        $house->id = $_GET['id'];
        $house->verify('id');
        return ['numbers' => model_house::get_numbers(model_session::get_company(), $house),
                'house' => $house];
    }

    public static function private_get_meters(){
        $company = model_session::get_company();
        $model = new model_number2meter($company, $_GET['id']);
        $meters = $model->get_meters();
        $enable_meters = $disable_meters = [];
        if(!empty($meters))
            foreach($meters as $meter)
                if($meter->get_status() == 'enabled')
                    $enable_meters[] = $meter;
                elseif($meter->get_status() == 'disabled')
                    $disable_meters[] = $meter;
        model_session::set_setting_param('number', 'number_content', 'meters');
        $model = new model_number($company);
        return ['number' => $model->get_number($_GET['id']),
                'enable_meters' => $enable_meters, 'disable_meters' => $disable_meters];
    }

    public static function private_get_number_content(){
        $switch = model_session::get_setting_param('number', 'number_content');
        switch($switch){
            case 'meters':
                $company = model_session::get_company();
                $model = new model_number2meter($company, $_GET['id']);
                $meters = $model->get_meters();
                $enable_meters = $disable_meters = [];
                if(!empty($meters))
                    foreach($meters as $meter)
                        if($meter->get_status() == 'enabled')
                            $enable_meters[] = $meter;
                        elseif($meter->get_status() == 'disabled')
                            $disable_meters[] = $meter;
                $model = new model_number($company);
                return ['number' => $model->get_number($_GET['id']),
                        'enable_meters' => $enable_meters, 'disable_meters' => $disable_meters,
                        'setting' => $switch];
            break;

            case 'centers':
                $c2n = new data_processing_center2number();
                $c2n->number_id = $_GET['id'];
                $c2n->verify('number_id');
                $company = model_session::get_company();
                $model = new model_number(model_session::get_company());
                return ['centers' => model_processing_center2number::get_processing_centers($company, $c2n),
                        'number' => $model->get_number($_GET['id']),
                        'setting' => $switch];
            break;

            default:
                $model = new model_number(model_session::get_company());
                return ['number' => $model->get_number($_GET['id'])];
        }
    }

    public static function private_get_number_information(){
        model_session::set_setting_param('number', 'number_content', 'information');
        $model = new model_number(model_session::get_company());
        return ['number' => $model->get_number($_GET['id'])];
    }

    public static function private_get_meter_data(){
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

    public static function private_get_meter_value(){
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

    public static function private_get_meter_cart(){
        $time = getdate();
        $time_begin = mktime(12, 0, 0, 1, 1, $time['year']);
        $time_end = mktime(12, 0, 0, 12, 1, $time['year']);
        $company = model_session::get_company();
        $model = new model_number2meter($company, $_GET['number_id']);
        $meter = $model->get_meter($_GET['meter_id'], $_GET['serial']);
        $model = new model_number($company);
        $number = $model->get_number($_GET['number_id']);
        $model = new model_meter2data(model_session::get_company(), $_GET['number_id'],
                                        $_GET['meter_id'], $_GET['serial']);
        return ['meter' => $meter,
                'number' => $number, 'time' => $time_begin,
                'meter_data' => $model->get_values($time_begin, $time_end)];
    }

    public static function private_get_meter_docs(){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }

    public static function private_get_meter_info(){
        $model = new model_number2meter(model_session::get_company(), $_GET['id']);
        return ['meter' => $model->get_meter($_GET['meter_id'], $_GET['serial'])];
    }
    
    public static function private_get_meter_options(){
        $model = new model_meter(model_session::get_company());
        return ['meters' => $model->get_meters_by_service($_GET['service'])];
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

    public static function private_update_date_checking(){
        $time = explode('.', $_GET['date']);
        $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
        $model = new model_number2meter(model_session::get_company(), $_GET['number_id']);
        return ['meter' => $model->update_date_checking($_GET['meter_id'], $_GET['serial'], $time)];
    }

    public static function private_update_date_install(){
        $time = explode('.', $_GET['date']);
        $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
        $model = new model_number2meter(model_session::get_company(), $_GET['number_id']);
        return ['meter' => $model->update_date_install($_GET['meter_id'], $_GET['serial'], $time)];
    }

    public static function private_update_date_release(){
        $time = explode('.', $_GET['date']);
        $time = mktime(12, 0, 0, $time[1], $time[0], $time[2]);
        $model = new model_number2meter(model_session::get_company(), $_GET['number_id']);
        return ['meter' => $model->update_date_release($_GET['meter_id'], $_GET['serial'], $time)];
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

    public static function private_update_serial(){
        $meter = new data_number2meter();
        $meter->set_number_id($_GET['number_id']);
        $meter->set_meter_id($_GET['meter_id']);
        $meter->set_serial($_GET['serial']);
        $model = new model_number2meter(model_session::get_company(), $_GET['number_id']);
        return ['old_meter' => $meter,
                'new_meter' => $model->update_serial($_GET['meter_id'], $_GET['serial'], $_GET['new_serial'])];
    }

    public static function private_update_meter_comment(){
        $model = new model_number2meter(model_session::get_company(), $_GET['number_id']);
        return ['meter' => $model->update_comment($_GET['meter_id'], $_GET['serial'], $_GET['comment'])];
    }

    public static function private_update_period(){
        $period = ((int) $_GET['year']*12) + (int) $_GET['month'];
        $model = new model_number2meter(model_session::get_company(), $_GET['number_id']);
        return ['meter' => $model->update_period($_GET['meter_id'], $_GET['serial'], $period)];
    }

    public static function private_update_meter_status(){
        $company = model_session::get_company();
        $model = new model_number2meter($company, $_GET['number_id']);
        $model->update_status($_GET['meter_id'], $_GET['serial']);
        $meters = $model->get_meters();
        $enable_meters = $disable_meters = [];
        if(!empty($meters))
            foreach($meters as $meter)
                if($meter->get_status() == 'enabled')
                    $enable_meters[] = $meter;
                elseif($meter->get_status() == 'disabled')
                    $disable_meters[] = $meter;
        return ['data' => $meter,
                'enable_meters' => $enable_meters, 'disable_meters' => $disable_meters];
    }

    public static function private_update_meter_place(){
        $model = new model_number2meter(model_session::get_company(), $_GET['number_id']);
        return ['meter' => $model->update_place($_GET['meter_id'], $_GET['serial'], $_GET['place'])];
    }
}