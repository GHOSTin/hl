<?php
/*
* Связь с таблицей `numbers`.
* Лицевые счета ассоциированы с компанией и с городом.
* В одном городе у одной компании не может быть два одинаковых лицевых счета.
*/
class data_number2meter extends data_object{

	private $company_id;
	private $number_id;
	private $meter_id;
	private $service;
    private $capacity;
	private $name;
	private $rates;
	private $serial;
	private $date_release;
	private $date_install;
	private $date_checking;
    private $date_next_checking;
	private $period;
	private $place;
    private $comment;
    private $status;

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

    public function get_capacity(){
        return $this->capacity;
    }

    public function get_comment(){
        return $this->comment;
    }

    public function get_company_id(){
        return $this->company_id;
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

    public function get_meter_id(){
        return $this->meter_id;
    }

    public function get_name(){
        return $this->name;
    }

    public function get_date_next_checking(){
        return $this->date_next_checking;
    }

    public function get_number_id(){
        return $this->number_id;
    }

    public function get_period(){
        return $this->period;
    }

    public function get_place(){
        return $this->place;
    }

    public function get_rates(){
        return $this->rates;
    }

    public function get_serial(){
        return $this->serial;
    }

    public function get_service(){
        return $this->service;
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
    
    public function set_service($service){
        $this->service = $service;
    }

    public function set_status($status){
        $this->status = $status;
    }
}