<?php
class controller_meter{

    public static function private_add_period(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->periods[] = $_GET['period'];
        return ['meter' => model_meter::add_period(model_session::get_company(), $meter)];
    }

    public static function private_add_service(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->service[] = $_GET['service'];
        return ['meter' => model_meter::add_service(model_session::get_company(), $meter)];
    }

    public static function private_create_meter(){
        $model = new model_meter(model_session::get_company());
        $model->create_meter($_GET['name'], $_GET['capacity'], $_GET['rates']);
        return ['meters' => $model->get_meters(new data_meter())];
    }

    public static function private_get_dialog_add_period(){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->get_meter($_GET['id'])];
    }

    public static function private_get_dialog_add_service(){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->get_meter($_GET['id'])];
    }

    public static function private_get_dialog_create_meter(){
        return true;
    }

    public static function private_get_dialog_edit_capacity(){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->get_meter($_GET['id'])];
    }

    public static function private_get_dialog_edit_rates(){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->get_meter($_GET['id'])];
    }

    public static function private_get_dialog_remove_period(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->periods[0] = $_GET['period'];
        $meter->verify('id', 'periods');
        return ['meter' => $meter];
    }

    public static function private_get_dialog_remove_service(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->service[0] = $_GET['service'];
        $meter->verify('id', 'service');
        return ['meter' => $meter];
    }

    public static function private_get_dialog_rename_meter(){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->get_meter($_GET['id'])];
    }

    public static function private_get_meter_content(){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->get_meter($_GET['id'])];
    }

    public static function private_remove_period(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->periods[0] = $_GET['period'];
        return ['meter' => model_meter::remove_period(model_session::get_company(), $meter)];
    }

    public static function private_remove_service(){
        $meter = new data_meter();
        $meter->id = $_GET['id'];
        $meter->service[0] = $_GET['service'];
        return ['meter' => model_meter::remove_service(model_session::get_company(), $meter)];
    }

    public static function private_rename_meter(){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->rename_meter($_GET['id'], $_GET['name'])];
    }

    public static function private_show_default_page(){
        $model = new model_meter(model_session::get_company());
        return ['meters' => $model->get_meters(new data_meter())];
    }

    public static function private_update_capacity(){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->update_capacity($_GET['id'], $_GET['capacity'])];
    }

    public static function private_update_rates(){
        $model = new model_meter(model_session::get_company());
        return ['meter' => $model->update_rates($_GET['id'], $_GET['rates'])];
    }
}