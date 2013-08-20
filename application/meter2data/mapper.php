<?php
class mapper_meter2data{

    private $company;
    private $number_id;

    public function __construct($company, $number_id, $meter_id, $serial){
        $this->company = $company;
        $this->number_id = (int) $number_id;
        $this->meter_id = (int) $meter_id;
        $this->serial = (string) $serial;
        // верификация
        $this->company->verify('id');
        $meter = new data_number2meter($this->company, $this->number_id);
        $meter->set_number_id($this->number_id);
        $meter->set_meter_id($this->meter_id);
        $meter->set_serial($this->serial);
        $meter->verify('number_id', 'meter_id', 'serial');
    }

    public function insert(data_meter2data $data){
        $data->set_company_id($this->company->id);
        $data->set_number_id($this->number_id);
        $data->set_meter_id($this->meter_id);
        $data->set_serial($this->serial);
        $data->verify('company_id', 'number_id', 'meter_id', 'serial', 'time',
                        'timestamp', 'value', 'way', 'comment');
        $sql = new sql();
        $sql->query("INSERT INTO `meter2data` (`company_id`, `number_id`, `meter_id`,
                `serial`, `time`, `timestamp`, `value`, `way`, `comment`)
                VALUES (:company_id, :number_id, :meter_id, :serial, :time, :timestamp,
                :value, :way, :comment)");
        $sql->bind(':company_id', $data->get_company_id(), PDO::PARAM_INT);
        $sql->bind(':number_id', $data->get_number_id(), PDO::PARAM_INT);
        $sql->bind(':meter_id', $data->get_meter_id(), PDO::PARAM_INT);
        $sql->bind(':serial', $data->get_serial(), PDO::PARAM_STR);
        $sql->bind(':time', $data->get_time(), PDO::PARAM_INT);
        $sql->bind(':timestamp', $data->get_timestamp(), PDO::PARAM_INT);
        $sql->bind(':value', implode(';', $data->get_value()), PDO::PARAM_STR);
        $sql->bind(':way', $data->get_way(), PDO::PARAM_STR);
        $sql->bind(':comment', $data->get_comment(), PDO::PARAM_STR);
        $sql->execute('Проблемы при создании показания.');
        return $data;
    }

    public function find($time){
        $time = getdate($time);
        $time = mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']);
        $sql = new sql();
        $sql->query("SELECT `company_id`, `number_id`, `meter_id`, `serial`,
                    `time`, `timestamp`, `value`, `way`, `comment`
                    FROM `meter2data`
                    WHERE `meter2data`.`company_id` = :company_id
                    AND `meter2data`.`number_id` = :number_id
                    AND `meter2data`.`meter_id` = :meter_id
                    AND `meter2data`.`serial` = :serial
                    AND `meter2data`.`time` = :time");
        $sql->bind(':number_id', $this->number_id, PDO::PARAM_INT);
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':meter_id', $this->meter_id, PDO::PARAM_INT);
        $sql->bind(':serial', $this->serial, PDO::PARAM_STR);
        $sql->bind(':time', $time, PDO::PARAM_INT);
        $data = $sql->map(new data_meter2data(), 'Проблема при запросе показания счетчика.');
        $count = count($data);
        if($count === 0)
            return null;
        if($count !== 1)
            throw new e_model('Неожиданное количество возвращаемых строк.');
        return  $data[0];
    }

    /*
    * Возвращает предидущее показание
    */
    public function last($time){
        $time = getdate($time);
        $time = mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']);
        $sql = new sql();
        $sql->query("SELECT`company_id`, `number_id`, `meter_id`, `serial`,
                    `time`, `timestamp`, `value`, `way`, `comment`
                    FROM `meter2data`
                    WHERE `company_id` = :company_id AND `number_id` = :number_id
                    AND `meter_id` = :meter_id AND `serial` = :serial
                    AND `time` = (SELECT MAX(`time`) FROM `meter2data` WHERE 
                    `company_id` = :company_id AND `number_id` = :number_id
                    AND `meter_id` = :meter_id AND `serial` = :serial AND `time` < :time)");
        $sql->bind(':number_id', $this->number_id, PDO::PARAM_INT);
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':meter_id', $this->meter_id, PDO::PARAM_INT);
        $sql->bind(':serial', $this->serial, PDO::PARAM_STR);
        $sql->bind(':time', $time, PDO::PARAM_INT);
        $data = $sql->map(new data_meter2data(), 'Проблема при запросе показания счетчика.');
        $count = count($data);
        if($count === 0)
            return null;
        if($count !== 1)
            throw new e_model('Неожиданное количество возвращаемых строк.');
        return  $data[0];
    }
}