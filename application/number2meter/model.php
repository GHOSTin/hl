<?php
class model_number2meter{

    private $number_id;
    private $company;

    public function __construct(data_company $company, $id){
        $this->company = $company;
        $this->number_id = (int) $id;
        if($number->id > 16777215 OR $this->number_id < 1)
            throw new e_model('Идентификатор лицевого счета задан не верно.');
    }

    public function get_meter($meter_id, $serial){
        $mapper = new mapper_number2meter($this->company, $this->number_id);
        $meter = $mapper->find($meter_id, $serial);
        if(!($meter instanceof data_number2meter))
            throw new e_model('Связи счетчик лицевой не существует.');
        return $meter;
    }

    /*
    * Возвращает список счетчиков лицевого счета
    */
    public function get_meters(){
        $this->company->verify('id');
        $sql = new sql();
        $sql->query("SELECT `number2meter`.`meter_id`, `number2meter`.`status`, `number2meter`.`number_id`,
                        `meters`.`name`, `meters`.`rates`, `meters`.`capacity`,
                        `number2meter`.`service`, `number2meter`.`serial`,
                        `number2meter`.`date_release`, `number2meter`.`date_install`,
                        `number2meter`.`date_checking`, `number2meter`.`period`,
                        `number2meter`.`place`, `number2meter`.`comment`, `number2meter`.`service`
                    FROM `meters`, `number2meter`
                    WHERE `number2meter`.`company_id` = :company_id
                    AND `meters`.`company_id` = :company_id
                    AND `meters`.`company_id` = :company_id
                    AND `number2meter`.`number_id` = :number_id
                    AND `meters`.`id` = `number2meter`.`meter_id`");
        $sql->bind(':number_id', $this->number_id, PDO::PARAM_INT);
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        return $sql->map(new data_number2meter(), 'Проблема при при выборке счетчиков лицевого счета.');
    }
}