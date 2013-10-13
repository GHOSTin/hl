<?php
class mapper_number{

    private $company;

    public function __construct(data_company $company){
        $this->company = $company;
        $this->company->verify('id');
    }

    public function create_object(array $row){
        $number = new data_number();
        $number->set_id($row['id']);
        $number->set_fio($row['fio']);
        $number->set_number($row['number']);
        $number->set_status($row['status']);
        $number->set_house_id($row['house_id']);
        $number->set_hash($row['password']);
        return $number;
    }

    public function find($id){
        $number = new data_number();
        $number->set_id($id);
        $number->verify('id');
        $sql = new sql();
        $sql->query("SELECT `numbers`.`id`, `numbers`.`company_id`, 
                        `numbers`.`city_id`, `numbers`.`house_id`, 
                        `numbers`.`flat_id`, `numbers`.`number`,
                        `numbers`.`type`, `numbers`.`status`,
                        `numbers`.`fio`, `numbers`.`telephone`,
                        `numbers`.`cellphone`, `numbers`.`password`,
                        `numbers`.`contact-fio` as `contact_fio`,
                        `numbers`.`contact-telephone` as `contact_telephone`,
                        `numbers`.`contact-cellphone` as `contact_cellphone`,
                        `flats`.`flatnumber` as `flat_number`,
                        `houses`.`housenumber` as `house_number`,
                        `houses`.`department_id`,
                        `streets`.`name` as `street_name`
                    FROM `numbers`, `flats`, `houses`, `streets`
                    WHERE `numbers`.`company_id` = :company_id
                    AND `numbers`.`id` = :number_id
                    AND `numbers`.`flat_id` = `flats`.`id`
                    AND `numbers`.`house_id` = `houses`.`id`
                    AND `houses`.`street_id` = `streets`.`id`");
        $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
        $sql->bind(':number_id', $number->get_id(), PDO::PARAM_INT);
        $sql->execute('Проблема при запросе лицевого счета.');
        $stmt = $sql->get_stm();
        $count = $stmt->rowCount();
        if($count === 0)
            return null;
        elseif($count === 1)
            return $this->create_object($stmt->fetch());
        if($count !== 1)
            throw new e_model('Неожиданное количество возвращаемых лицевых счетов.');
    }

    public function find_by_number($num){
        $number = new data_number();
        $number->set_number($num);
        $number->verify('number');
        $sql = new sql();
        $sql->query("SELECT `numbers`.`id`, `numbers`.`company_id`, 
                        `numbers`.`city_id`, `numbers`.`house_id`, 
                        `numbers`.`flat_id`, `numbers`.`number`,
                        `numbers`.`type`, `numbers`.`status`,
                        `numbers`.`fio`, `numbers`.`telephone`,
                        `numbers`.`cellphone`, `numbers`.`password`,
                        `numbers`.`contact-fio` as `contact_fio`,
                        `numbers`.`contact-telephone` as `contact_telephone`,
                        `numbers`.`contact-cellphone` as `contact_cellphone`,
                        `flats`.`flatnumber` as `flat_number`,
                        `houses`.`housenumber` as `house_number`,
                        `houses`.`department_id`,
                        `streets`.`name` as `street_name`
                    FROM `numbers`, `flats`, `houses`, `streets`
                    WHERE `numbers`.`company_id` = :company_id
                    AND `numbers`.`number` = :number
                    AND `numbers`.`flat_id` = `flats`.`id`
                    AND `numbers`.`house_id` = `houses`.`id`
                    AND `houses`.`street_id` = `streets`.`id`");
        $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
        $sql->bind(':number', (string) $num, PDO::PARAM_STR);
        $sql->execute('Проблема при запросе лицевого счета.');
        $stmt = $sql->get_stm();
        $count = $stmt->rowCount();
        if($count === 0)
            return null;
        elseif($count === 1)
            return $this->create_object($stmt->fetch());
        if($count !== 1)
            throw new e_model('Неожиданное количество возвращаемых лицевых счетов.');
    }

    public function update(data_number $number){
        $number->verify('id', 'number', 'fio', 'telephone');
        $sql = new sql();
        $sql->query('UPDATE `numbers` SET `number` = :number, `fio` = :fio,
            `cellphone` = :cellphone, `telephone` = :telephone, `password` = :hash
            WHERE `company_id` = :company_id AND `id` = :id');
        $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
        $sql->bind(':id', $number->get_id(), PDO::PARAM_INT);
        $sql->bind(':number', $number->get_number(), PDO::PARAM_STR);
        $sql->bind(':fio', $number->get_fio(), PDO::PARAM_STR);
        $sql->bind(':telephone', $number->get_telephone(), PDO::PARAM_STR);
        $sql->bind(':cellphone', $number->get_cellphone(), PDO::PARAM_STR);
        $sql->bind(':hash', $number->get_hash(), PDO::PARAM_STR);
        $sql->execute('Проблема при обнослении лицевого счета.');
        return $number;
    }
}