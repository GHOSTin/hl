<?php
class mapper_user{

    public function find($id){
        $user = new data_user();
        $user->id = $id;
        $user->verify('id');
        $sql = new sql();
        $sql->query("SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
                `users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
                `users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
                `users`.`cellphone` FROM `users` WHERE `id` = :id");
        $sql->bind(':id', $id, PDO::PARAM_INT);
        $users = $sql->map(new data_user(), 'Проблема при выборке пользователя.');
        if(count($users) === 0)
            throw new e_model('Пользователя не существует');
        if(count($users) !== 1)
            throw new e_model('Неожиданное количество пользователей.');
        $user = $users[0];
        model_user::is_data_user($user);
        return $user;
    }
}