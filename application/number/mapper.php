<?php
class mapper_number{

    public function find(data_company $company, $id){
        $company->verify('id');
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
        $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
        $sql->bind(':number_id', $number->id, PDO::PARAM_INT);
        $numbers = $sql->map(new data_number(), 'Проблема при запросе лицевого счета.');
        $count = count($numbers);
        if($count === 0)
            return null;
        if($count !== 1)
            throw new e_model('Неожиданное количество возвращаемых лицевых счетов.');
        return  $numbers[0];
    }
}