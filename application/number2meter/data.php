<?php
/*
* Связь с таблицей `numbers`.
* Лицевые счета ассоциированы с компанией и с городом.
* В одном городе у одной компании не может быть два одинаковых лицевых счета.
*/
final class data_number2meter extends data_object{

	public $company_id;
	public $number_id;
	public $meter_id;
	public $service;
    public $capacity;
	public $name;
	public $rates;
	public $serial;
	public $date_release;
	public $date_install;
	public $date_checking;
    public $date_next_checking;
	public $period;
	public $place;
    public $comment;
    public $status;

    public function __construct(){
        if(!empty($this->date_checking) AND !empty($this->period)){
            $this->date_next_checking = strtotime('+'.$this->period.' month', $this->date_checking);
        }
    }

	public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_number2meter::$value($this);
    }

    public function get_comment(){
        return $this->comment;
    }

    public function get_date_checking(){
        return $this->date_checking;
    }

    public function get_date_install(){
        return $this->date_install;
    }

    public function get_date_release(){
        return $this->date_release;
    }

    public function get_place(){
        return $this->place;
    }

    public function get_serial(){
        return $this->serial;
    }

    public function get_status(){
        return $this->status;
    }

    public function set_comment($comment){
        $this->comment = $comment;
    }

    public function set_company_id($id){
        $this->company_id = $id;
    }

    public function set_date_checking($time){
        $this->date_checking = $time;
    }

    public function set_date_install($time){
        $this->date_install = $time;
    }

    public function set_date_release($time){
        $this->date_release = $time;
    }

    public function set_meter_id($id){
        $this->meter_id = $id;
    }

    public function set_number_id($id){
        $this->number_id = $id;
    }

    public function set_period($period){
        $this->period = $period;
    }

    public function set_place($place){
        $this->place = $place;
    }

    public function set_serial($serial){
        $this->serial = $serial;
    }

    public function set_status($status){
        $this->status = $status;
    }
}