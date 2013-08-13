<?php
class model_meter2data{

    private $company;
    private $number_id;
    private $meter_id;
    private $serial;

    public function __construct(data_company $company, $number_id, $meter_id, $serial){
        $this->company = $company;
        $this->number_id = $number_id;
        $this->meter_id = $meter_id;
        $this->serial = $serial;
    }

    public function get_values($begin, $end){
        $this->company->verify('id');
        if(empty($begin) OR empty($end))
            throw new e_model('Время начала выборки больше чем время конца выборки.');
        $sql = new sql();
        $sql->query("SELECT `time`, `value`, `comment`, `way`, `timestamp` FROM `meter2data`
                    WHERE `meter2data`.`company_id` = :company_id
                    AND `meter2data`.`number_id` = :number_id
                    AND `meter2data`.`meter_id` = :meter_id
                    AND `meter2data`.`serial` = :serial
                    AND `meter2data`.`time` >= :time_begin
                    AND `meter2data`.`time` <= :time_end");
        $sql->bind(':meter_id', $this->meter_id, PDO::PARAM_INT);
        $sql->bind(':serial', $this->serial, PDO::PARAM_STR);
        $sql->bind(':number_id', $this->number_id, PDO::PARAM_INT);
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':time_begin', $begin, PDO::PARAM_INT);
        $sql->bind(':time_end', $end, PDO::PARAM_INT);
        $sql->execute( 'Проблема при при выборки данных счетчика.');
        $result = [];
        while($row = $sql->row()){
            $data = new data_meter2data();
            $data->time = $row['time'];
            $data->comment = $row['comment'];
            $data->way = $row['way'];
            $data->timestamp = $row['timestamp'];
            if(empty($row['value']))
                $data->value = [];
            else
                $data->value = explode(';', $row['value']);
            $result[$data->time] = $data;
        }
        return $result;
    }
}