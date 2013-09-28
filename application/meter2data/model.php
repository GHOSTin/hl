<?php
class model_meter2data{

    private $company;
    private $number;
    private $meter;
    private $n2m;

    public function __construct(data_company $company, data_number2meter $n2m){
        $this->company = $company;
        $this->company->verify('id');
        $this->number = $n2m->get_number();
        $this->number->verify('id');
        $this->meter = $n2m->get_meter();
        $this->meter->verify('id');
        $this->n2m = $n2m;
        $this->n2m->verify('serial');
    }

    public function get_values($begin, $end){
        $sql = new sql();
        $sql->query("SELECT `time`, `value`, `comment`, `way`, `timestamp`
            FROM `meter2data`
                    WHERE `meter2data`.`company_id` = :company_id
                    AND `meter2data`.`number_id` = :number_id
                    AND `meter2data`.`meter_id` = :meter_id
                    AND `meter2data`.`serial` = :serial
                    AND `meter2data`.`time` >= :time_begin
                    AND `meter2data`.`time` <= :time_end");
        $sql->bind(':meter_id', $this->meter->get_id(), PDO::PARAM_INT);
        $sql->bind(':serial', $this->n2m->get_serial(), PDO::PARAM_STR);
        $sql->bind(':number_id', $this->number->get_id(), PDO::PARAM_INT);
        $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
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

    public function init_values($begin, $end){
        $values = $this->get_values($begin, $end);
    }

    public function update_value($time, array $values, $way, $comment, $timestamp){
        $model = new model_number2meter($this->company, $this->number_id);
        $meter = $model->get_meter($this->meter_id, $this->serial);
        if(count($values) !== (int) $meter->get_rates())
            throw new e_model('Количество показаний не равно тарифности счетчика.');
        $mapper = new mapper_meter2data($this->company, $this->number_id, $this->meter_id, $this->serial);
        $current = $mapper->find($time);
        if(!is_null($current)){
            $current->set_way($way);
            $current->set_comment($comment);
            $current->set_value($values);
            $new = false;
        }else{
            $data = new data_meter2data();
            $data->set_way($way);
            $data->set_comment($comment);
            $data->set_value($values);
            $data->set_time($time);
            $data->set_timestamp(time());
            $new = true;
        }
        $last = $mapper->last($time);
        // if(!is_null($last))
        //     die('CHECK');
        if($new)
            $mapper->insert($data);
        else
            $mapper->update($current);
        return $meter;
    }
}