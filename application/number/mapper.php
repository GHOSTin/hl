<?php
class mapper_number{

    private $company;

    public function __construct(data_company $company){
        $this->company = $company;
    }

    public function find($id){
        $this->company->verify('id');
        $number = new data_number();
        $number->id = $id;
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
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':number_id', $number->id, PDO::PARAM_INT);
        $numbers = $sql->map(new data_number(), 'Проблема при запросе лицевого счета.');
        $count = count($numbers);
        if($count === 0)
            return null;
        if($count !== 1)
            throw new e_model('Неожиданное количество возвращаемых лицевых счетов.');
        return  $numbers[0];
    }

    public function find_by_number($num){
        $this->company->verify('id');
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
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':number', $num, PDO::PARAM_STR);
        $numbers = $sql->map(new data_number(), 'Проблема при запросе лицевого счета.');
        $count = count($numbers);
        if($count === 0)
            return null;
        if($count !== 1)
            throw new e_model('Неожиданное количество возвращаемых лицевых счетов.');
        return  $numbers[0];
    }

    public function update(data_number $number){
        $number->verify('id', 'number');
        $this->company->verify('id');
        $sql = new sql();
        $sql->query('UPDATE `numbers` SET `number` = :number
            WHERE `company_id` = :company_id AND `id` = :id');
        $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
        $sql->bind(':id', $number->id, PDO::PARAM_INT);
        $sql->bind(':number', $number->number, PDO::PARAM_STR);
        $sql->execute('Проблема при обнослении лицевого счета.');
        return $user;
    }
}