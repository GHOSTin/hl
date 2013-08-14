<?php
class mapper_number2meter{

    private $company;
    private $number_id;

    public function __construct($company, $number_id){
        $this->company = $company;
        $this->number_id = $number_id;
    }

    public function find($meter_id, $serial){
        $this->company->verify('id');
        $meter = new data_meter();
        $meter->id = $meter_id;
        $meter->verify('id');
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
                    AND `number2meter`.`meter_id` = :meter_id
                    AND `number2meter`.`serial` = :serial
                    AND `number2meter`.`number_id` = :number_id
                    AND `meters`.`id` = `number2meter`.`meter_id`");
        $sql->bind(':number_id', $this->number_id, PDO::PARAM_INT);
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':meter_id', $meter_id, PDO::PARAM_INT);
        $sql->bind(':serial', $serial, PDO::PARAM_STR);
        $meters = $sql->map(new data_number2meter(), 'Проблема при запросе счетчика.');
        $count = count($meters);
        if($count === 0)
            return null;
        if($count !== 1)
            throw new e_model('Неожиданное количество возвращаемых счетчиков.');
        return  $meters[0];
    }

    public function update(data_number2meter $meter){
        $meter->set_company_id($this->company->id);
        $meter->verify('company_id', 'number_id', 'meter_id', 'serial', 'period',
                        'status', 'place', 'comment', 'date_release');
        $sql = new sql();
        $sql->query("UPDATE `number2meter` SET `period` = :period, `status` = :status,
            `place` = :place, `comment` = :comment, `date_release` = :date_release
            WHERE `company_id` = :company_id AND `number_id` = :number_id
            AND `meter_id` = :meter_id AND `serial` = :serial");
        $sql->bind(':number_id', $this->number_id, PDO::PARAM_INT);
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':meter_id', $meter->meter_id, PDO::PARAM_INT);
        $sql->bind(':serial', $meter->serial, PDO::PARAM_STR);
        $sql->bind(':period', $meter->period, PDO::PARAM_INT);
        $sql->bind(':status', $meter->status, PDO::PARAM_STR);
        $sql->bind(':date_release', $meter->get_date_release(), PDO::PARAM_INT);
        $sql->bind(':place', $meter->get_place(), PDO::PARAM_STR);
        $sql->bind(':comment', $meter->get_comment(), PDO::PARAM_STR);
        $sql->execute('Проблема при обновлении связи лицевого счета и счетчика');
        return $meter;
    }

    public function update_serial(data_number2meter $meter, $serial){
        $meter->set_company_id($this->company->id);
        $meter->verify('company_id', 'number_id', 'meter_id', 'serial', 'period',
                        'status', 'place', 'comment', 'date_release');
        $m = new data_number2meter();
        $m->set_serial($serial);
        $m->verify('serial');
        $sql = new sql();
        $sql->query("UPDATE `number2meter` SET `period` = :period, `status` = :status, `serial` = :new_serial,
            `place` = :place, `comment` = :comment, `date_release` = :date_release
            WHERE `company_id` = :company_id AND `number_id` = :number_id
            AND `meter_id` = :meter_id AND `serial` = :serial");
        $sql->bind(':number_id', $this->number_id, PDO::PARAM_INT);
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':meter_id', $meter->meter_id, PDO::PARAM_INT);
        $sql->bind(':serial', $meter->serial, PDO::PARAM_STR);
        $sql->bind(':period', $meter->period, PDO::PARAM_INT);
        $sql->bind(':status', $meter->status, PDO::PARAM_STR);
        $sql->bind(':date_release', $meter->get_date_release(), PDO::PARAM_INT);
        $sql->bind(':place', $meter->get_place(), PDO::PARAM_STR);
        $sql->bind(':new_serial', $m->get_serial(), PDO::PARAM_STR);
        $sql->bind(':comment', $meter->get_comment(), PDO::PARAM_STR);
        $sql->execute('Проблема при обновлении связи лицевого счета и счетчика');
        $meter->set_serial($serial);
        return $meter;
    }
}