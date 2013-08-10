<?php
class mapper_user{

    public function find($id){
        try{
            $user = new data_user();
            $user->id = $id;
            $user->verify('id');
            $sql = new sql();
            $sql->query("SELECT `id`, `company_id`, `status`, `username` as `login`,
                        `firstname`, `lastname`, `midlename` as `middlename`, `password`,
                        `telephone`, `cellphone` FROM `users` WHERE `id` = :id");
            $sql->bind(':id', $id, PDO::PARAM_INT);
            $users = $sql->map(new data_user(), 'Проблема при выборке пользователя.');
            if(count($users) === 0)
                throw new e_model('Пользователя не существует');
            if(count($users) !== 1)
                throw new e_model('Неожиданное количество пользователей.');
            $user = $users[0];
            $this->is_data_user($user);
            return $user;
        }catch(exception $e){
            return null;
        }
    }

    private function get_insert_id(){
        $sql = new sql();
        $sql->query("SELECT MAX(`id`) as `max_user_id` FROM `users`");
        $sql->execute('Проблема при опредении следующего user_id.');
        if($sql->count() !== 1)
            throw new e_model('Проблема при опредении следующего user_id.');
        $user_id = (int) $sql->row()['max_user_id'] + 1;
        $sql->close();
        return $user_id;
    }

    public function insert(data_user $user){
        $login = $user->login;
        $sql = new sql();
        $sql->query("SELECT `id` FROM `users` WHERE `username` = :login");
        $sql->bind(':login', $login, PDO::PARAM_STR);
        $sql->execute("Ошибка при поиске идентичного логина.");
        if($sql->count() !== 0)
            throw new e_model('Пользователь с таким логином уже существует.');
        $user->id = $this->get_insert_id();
        $user->verify('firstname', 'lastname', 'middlename', 'login', 'status');
        if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $user->password))
            throw new e_model('Пароль не удовлетворяет a-zA-Z0-9 или меньше 8 символов.');
        $sql = new sql();
        $sql->query("INSERT INTO `users` (`id`, `company_id`, `status`, `username`,
                `firstname`, `lastname`, `midlename`, `password`, `telephone`, `cellphone`)
                VALUES (:user_id, :company_id, :status, :login, :firstname, :lastname, 
                :middlename, :password, :telephone, :cellphone)");
        $sql->bind(':user_id', $user->id, PDO::PARAM_INT);
        $sql->bind(':company_id', 2, PDO::PARAM_INT);
        $sql->bind(':status', $user->status, PDO::PARAM_STR);
        $sql->bind(':login', $user->login, PDO::PARAM_STR);
        $sql->bind(':firstname', $user->firstname, PDO::PARAM_STR);
        $sql->bind(':lastname', $user->lastname, PDO::PARAM_STR);
        $sql->bind(':middlename', $user->middlename, PDO::PARAM_STR);
        $sql->bind(':password', model_user::get_password_hash($user->password), PDO::PARAM_STR);
        $sql->bind(':telephone', $user->telephone, PDO::PARAM_STR);
        $sql->bind(':cellphone', $user->cellphone, PDO::PARAM_STR);
        $sql->execute('Проблемы при создании пользователя.');
        return $user;
    }

    public function update(data_user $user){
        $sql = new sql();
        $sql->query('UPDATE `users` SET `firstname` = :firstname, `lastname` = :lastname,
                    `midlename` = :middlename, `status` = :status, `password` = :password, `username` = :login WHERE `id` = :id');
        $user->verify('id', 'firstname', 'middlename', 'lastname', 'status', 'login');
        $sql->bind(':firstname', $user->firstname, PDO::PARAM_STR);
        $sql->bind(':lastname', $user->lastname, PDO::PARAM_STR);
        $sql->bind(':middlename', $user->middlename, PDO::PARAM_STR);
        $sql->bind(':status', $user->status, PDO::PARAM_STR);
        $sql->bind(':login', $user->login, PDO::PARAM_STR);
        $sql->bind(':password', $user->password, PDO::PARAM_STR);
        $sql->bind(':id', $user->id, PDO::PARAM_INT);
        $sql->execute('Проблемы при обвнолении записи пользователя.');
    }

    private function is_data_user($user){
        if(!($user instanceof data_user))
            throw new e_model('Возвращен объект не является пользователем');
    }
}