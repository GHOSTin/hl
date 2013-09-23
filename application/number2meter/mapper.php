<?php
class mapper_number2meter{

    private $number;
    private $company;
    private static $sql_get_meters = "SELECT `number2meter`.`meter_id`, 
            `number2meter`.`status`,
            `meters`.`name`, `meters`.`rates`, `meters`.`capacity`,
            `number2meter`.`service`, `number2meter`.`serial`,
            `number2meter`.`date_release`, `number2meter`.`date_install`,
            `number2meter`.`date_checking`, `number2meter`.`period`,
            `number2meter`.`place`, `number2meter`.`comment`
        FROM `meters`, `number2meter`
        WHERE `number2meter`.`company_id` = :company_id
        AND `meters`.`company_id` = :company_id
        AND `meters`.`company_id` = :company_id
        AND `number2meter`.`number_id` = :number_id
        AND `meters`.`id` = `number2meter`.`meter_id`";

    public function __construct(data_company $company, data_number $number){
        $this->company = $company;
        $this->number = $number;
        $this->company->verify('id');
        $this->number->verify('id');
    }

    public function create_object(array $row){
        $meter = new data_meter();
        $meter->set_id($row['meter_id']);
        $meter->set_name($row['name']);
        $meter->set_capacity($row['capacity']);
        $meter->set_rates($row['rates']);
        $n2m = new data_number2meter($meter);
        $n2m->set_serial($row['serial']);
        $n2m->set_service($row['service']);
        $n2m->set_status($row['status']);
        $n2m->set_date_release($row['date_release']);
        $n2m->set_date_install($row['date_install']);
        $n2m->set_date_checking($row['date_checking']);
        $n2m->set_period($row['period']);
        $n2m->set_place($row['place']);
        $n2m->set_comment($row['comment']);
        return $n2m;
    }

    public function delete(data_number2meter $meter){
        $meter->set_company_id($this->company->id);
        $meter->set_number_id($this->number_id);
        $meter->verify('company_id', 'number_id', 'meter_id', 'serial');
        $sql = new sql();
        $sql->query("DELETE FROM `number2meter` 
            WHERE `company_id` = :company_id AND `number_id` = :number_id
            AND `meter_id` = :meter_id AND `serial` = :serial");
        $sql->bind(':number_id', $meter->get_number_id(), PDO::PARAM_INT);
        $sql->bind(':company_id', $meter->get_company_id(), PDO::PARAM_INT);
        $sql->bind(':meter_id', $meter->get_meter_id(), PDO::PARAM_INT);
        $sql->bind(':serial', $meter->get_serial(), PDO::PARAM_STR);
        $sql->execute('Проблема при удалении связи лицевого счета и счетчика');
    }

    public function init_numbers(){
        $meters = $this->get_meters();
        if(!empty($meters))
            foreach($meters as $meter)
                $this->number->add_meter($meter);
    }

    /*
    * Возвращает список счетчиков лицевого счета
    */
    private function get_meters(){
        $sql = new sql();
        $sql->query(self::$sql_get_meters);
        $sql->bind(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
        $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
        $sql->execute('Проблема при при выборке счетчиков лицевого счета.');
        $stmt = $sql->get_stm();
        while($row = $stmt->fetch())
            $meters[] = $this->create_object($row);
        return $meters;
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

    public function insert(data_number2meter $n2m){
        $n2m->set_company_id($this->company->id);
        $n2m->set_number_id($this->number_id);
        $n2m->verify('company_id', 'number_id', 'meter_id', 'serial', 'service',
                    'status', 'place', 'date_release', 'date_install',
                    'date_checking', 'period', 'comment');
        $sql = new sql();
        $sql->query('INSERT INTO `number2meter` (`company_id`, `number_id`,
                    `meter_id`, `serial`, `status`, `service`, `place`,
                    `date_release`, `date_install`, `date_checking`, `period`,
                    `comment`) VALUES (:company_id, :number_id, :meter_id,
                    :serial, :status, :service, :place, :date_release,
                    :date_install, :date_checking, :period, :comment)');
        $sql->bind(':number_id', $n2m->get_number_id(), PDO::PARAM_INT);
        $sql->bind(':company_id', $n2m->get_company_id(), PDO::PARAM_INT);
        $sql->bind(':meter_id', $n2m->get_meter_id(), PDO::PARAM_INT);
        $sql->bind(':serial', $n2m->get_serial(), PDO::PARAM_STR);
        $sql->bind(':service', $n2m->get_service(), PDO::PARAM_STR);
        $sql->bind(':status', $n2m->get_status(), PDO::PARAM_STR);
        $sql->bind(':place', $n2m->get_place(), PDO::PARAM_STR);
        $sql->bind(':date_release', $n2m->get_date_release(), PDO::PARAM_INT);
        $sql->bind(':date_install', $n2m->get_date_install(), PDO::PARAM_INT);
        $sql->bind(':date_checking', $n2m->get_date_checking(), PDO::PARAM_INT);
        $sql->bind(':period', $n2m->get_period(), PDO::PARAM_INT);
        $sql->bind(':comment', $n2m->get_comment(), PDO::PARAM_STR);
        $sql->execute('Проблемы при вставке связи лицевой счет - счетчик.');
        return  $n2m;

    }

    public function update(data_number2meter $meter){
        $meter->set_company_id($this->company->id);
        $meter->set_number_id($this->number_id);
        $meter->verify('company_id', 'number_id', 'meter_id', 'serial', 'period',
                        'status', 'place', 'comment', 'date_release', 'date_install', 'date_checking');
        $sql = new sql();
        $sql->query("UPDATE `number2meter` SET `period` = :period, `status` = :status,
            `place` = :place, `comment` = :comment, `date_release` = :date_release,
            `date_install` = :date_install, `date_checking` = :date_checking
            WHERE `company_id` = :company_id AND `number_id` = :number_id
            AND `meter_id` = :meter_id AND `serial` = :serial");
        $sql->bind(':number_id', $meter->get_number_id(), PDO::PARAM_INT);
        $sql->bind(':company_id', $meter->get_company_id(), PDO::PARAM_INT);
        $sql->bind(':meter_id', $meter->get_meter_id(), PDO::PARAM_INT);
        $sql->bind(':serial', $meter->get_serial(), PDO::PARAM_STR);
        $sql->bind(':period', $meter->get_period(), PDO::PARAM_INT);
        $sql->bind(':status', $meter->get_status(), PDO::PARAM_STR);
        $sql->bind(':date_release', $meter->get_date_release(), PDO::PARAM_INT);
        $sql->bind(':date_install', $meter->get_date_install(), PDO::PARAM_INT);
        $sql->bind(':date_checking', $meter->get_date_checking(), PDO::PARAM_INT);
        $sql->bind(':place', $meter->get_place(), PDO::PARAM_STR);
        $sql->bind(':comment', $meter->get_comment(), PDO::PARAM_STR);
        $sql->execute('Проблема при обновлении связи лицевого счета и счетчика');
        return $meter;
    }

    public function update_serial(data_number2meter $meter, $serial){
        $meter->set_company_id($this->company->id);
        $meter->set_number_id($this->number_id);
        $meter->verify('company_id', 'number_id', 'meter_id', 'serial', 'period',
                        'status', 'place', 'comment', 'date_release', 'date_install', 'date_checking');
        $m = new data_number2meter();
        $m->set_serial($serial);
        $m->verify('serial');
        $sql = new sql();
        $sql->query("UPDATE `number2meter` SET `period` = :period, `status` = :status, `serial` = :new_serial,
            `place` = :place, `comment` = :comment, `date_release` = :date_release,
            `date_install` = :date_install, `date_checking` = :date_checking
            WHERE `company_id` = :company_id AND `number_id` = :number_id
            AND `meter_id` = :meter_id AND `serial` = :serial");
        $sql->bind(':number_id', $meter->get_number_id(), PDO::PARAM_INT);
        $sql->bind(':company_id', $meter->get_company_id(), PDO::PARAM_INT);
        $sql->bind(':meter_id', $meter->get_meter_id(), PDO::PARAM_INT);
        $sql->bind(':serial', $meter->get_serial(), PDO::PARAM_STR);
        $sql->bind(':period', $meter->get_period(), PDO::PARAM_INT);
        $sql->bind(':status', $meter->get_status(), PDO::PARAM_STR);
        $sql->bind(':date_release', $meter->get_date_release(), PDO::PARAM_INT);
        $sql->bind(':date_install', $meter->get_date_install(), PDO::PARAM_INT);
        $sql->bind(':date_checking', $meter->get_date_checking(), PDO::PARAM_INT);
        $sql->bind(':place', $meter->get_place(), PDO::PARAM_STR);
        $sql->bind(':new_serial', $m->get_serial(), PDO::PARAM_STR);
        $sql->bind(':comment', $meter->get_comment(), PDO::PARAM_STR);
        $sql->execute('Проблема при обновлении связи лицевого счета и счетчика');
        $meter->set_serial($serial);
        return $meter;
    }

    public function change(data_number2meter $meter, $old_meter_id, $old_serial){
        $meter->set_company_id($this->company->id);
        $meter->set_number_id($this->number_id);
        $meter->verify('company_id', 'number_id', 'meter_id', 'serial', 'period',
                        'status', 'place', 'comment', 'date_release', 'date_install', 'date_checking');
        $sql = new sql();
        $sql->query("UPDATE `number2meter` SET `meter_id` = :meter_id,
            `serial` = :serial, `period` = :period, `status` = :status, 
            `place` = :place, `comment` = :comment, `date_release` = :date_release,
            `date_install` = :date_install, `date_checking` = :date_checking
            WHERE `company_id` = :company_id AND `number_id` = :number_id
            AND `meter_id` = :old_meter_id AND `serial` = :old_serial");
        $sql->bind(':number_id', $meter->get_number_id(), PDO::PARAM_INT);
        $sql->bind(':company_id', $meter->get_company_id(), PDO::PARAM_INT);
        $sql->bind(':meter_id', $meter->get_meter_id(), PDO::PARAM_INT);
        $sql->bind(':serial', $meter->get_serial(), PDO::PARAM_STR);
        $sql->bind(':period', $meter->get_period(), PDO::PARAM_INT);
        $sql->bind(':status', $meter->get_status(), PDO::PARAM_STR);
        $sql->bind(':date_release', $meter->get_date_release(), PDO::PARAM_INT);
        $sql->bind(':date_install', $meter->get_date_install(), PDO::PARAM_INT);
        $sql->bind(':date_checking', $meter->get_date_checking(), PDO::PARAM_INT);
        $sql->bind(':place', $meter->get_place(), PDO::PARAM_STR);
        $sql->bind(':comment', $meter->get_comment(), PDO::PARAM_STR);
        $sql->bind(':old_meter_id', (int) $old_meter_id, PDO::PARAM_INT);
        $sql->bind(':old_serial', (string) $old_serial, PDO::PARAM_STR);
        $sql->execute('Проблема при обновлении связи лицевого счета и счетчика');
        return $meter;
    }
}