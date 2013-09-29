<?php
class model_number2meter{

    private $number;
    private $company;

    public function __construct(data_company $company, data_number $number){
        $this->company = $company;
        $this->number = $number;
        $this->company->verify('id');
        $this->number->verify('id');
    }

    public function add_meter($meter_id, $serial, $service, $place, $date_release,
                                $date_install, $date_checking, $period, $comment){
        $meter = (new model_meter($this->company))->get_meter($meter_id);
        $mapper = new mapper_number2meter($this->company, $this->number);
        $mapper->init_meters();
        $n2m = new data_number2meter($this->number, $meter);
        $n2m->set_serial($serial);
        $n2m->set_status('enabled');
        $n2m->set_service($service);
        $n2m->set_place($place);
        $n2m->set_date_release($date_release);
        $n2m->set_date_install($date_install);
        $n2m->set_date_checking($date_checking);
        $n2m->set_period($period);
        $n2m->set_comment($comment);
        $this->number->add_meter($n2m);
        $mapper->update_meter_list();
    }

    public function change_meter($old_meter_id, $old_serial, $meter_id, $serial, $service, $place, $date_release,
                                $date_install, $date_checking, $period, $comment){
        $model = new model_meter($this->company);
        $meter = $model->get_meter($meter_id);
        $mapper = new mapper_number2meter($this->company, $this->number_id);
        if($mapper->find($meter_id, $serial) !== null)
            throw new e_model('Счетчик с таким идентификатором и серийным номером уже существует.');
        $n2m = new data_number2meter();
        $n2m->set_meter_id($meter_id);
        $n2m->set_serial($serial);
        $n2m->set_status('enabled');
        $n2m->set_service($service);
        $n2m->set_place($place);
        $n2m->set_date_release($date_release);
        $n2m->set_date_install($date_install);
        $n2m->set_date_checking($date_checking);
        $n2m->set_period($period);
        $n2m->set_comment($comment);
        return $mapper->change($n2m, $old_meter_id, $old_serial);
    }

    public function get_meter($meter_id, $serial){
        $mapper = new mapper_number2meter($this->company, $this->number);
        $meter = $mapper->find($meter_id, $serial);
        if(!($meter instanceof data_number2meter))
            throw new e_model('Связи счетчик лицевой не существует.');
        return $meter;
    }

    public function init_meters(){
        $mapper = new mapper_number2meter($this->company, $this->number);
        $mapper->init_meters();
    }

    public function remove_meter($meter_id, $serial){
        $meter = $this->get_meter($meter_id, $serial);
        $mapper = new mapper_number2meter($this->company, $this->number_id);
        return $mapper->delete($meter);
    }
    
    public function update_date_checking($meter_id, $serial, $time){
        $meter = $this->get_meter($meter_id, $serial);
        $meter->set_date_checking($time);
        $mapper = new mapper_number2meter($this->company, $this->number);
        return $mapper->update($meter);
    }

    public function update_date_install($meter_id, $serial, $time){
        $meter = $this->get_meter($meter_id, $serial);
        $meter->set_date_install($time);
        $mapper = new mapper_number2meter($this->company, $this->number);
        return $mapper->update($meter);
    }

    public function update_date_release($meter_id, $serial, $time){
        $meter = $this->get_meter($meter_id, $serial);
        $meter->set_date_release($time);
        $mapper = new mapper_number2meter($this->company, $this->number);
        return $mapper->update($meter);
    }

    public function update_comment($meter_id, $serial, $comment){
        $meter = $this->get_meter($meter_id, $serial);
        $meter->set_comment($comment);
        $mapper = new mapper_number2meter($this->company, $this->number_id);
        return $mapper->update($meter);
    }

    public function update_period($meter_id, $serial, $period){
        $meter = $this->get_meter($meter_id, $serial);
        $meter->set_period($period);
        $mapper = new mapper_number2meter($this->company, $this->number_id);
        return $mapper->update($meter);
    }

    public function update_place($meter_id, $serial, $place){
        $meter = $this->get_meter($meter_id, $serial);
        if(!in_array($meter->get_service(), ['cold_water', 'hot_water']))
                throw new e_model('Вы не можете изменить место для счетчика с такой услугой.');
        $meter->set_place($place);
        $mapper = new mapper_number2meter($this->company, $this->number);
        return $mapper->update($meter);
    }

    public function update_serial($meter_id, $old_serial, $new_serial){
        $old_meter = $this->get_meter($meter_id, $old_serial);
        $mapper = new mapper_number2meter($this->company, $this->number);
        if(!is_null($mapper->find($meter_id, $new_serial)))
            throw new e_model('Счетчик с таким серийным номером уже привязан.');
        return $mapper->update_serial($old_meter, $new_serial);
    }

    public function update_status($meter_id, $serial){
        $meter = $this->get_meter($meter_id, $serial);
        if($meter->get_status() === 'enabled')
            $meter->set_status('disabled');
        else
            $meter->set_status('enabled');
        $mapper = new mapper_number2meter($this->company, $this->number);
        return $mapper->update($meter);
    }
}