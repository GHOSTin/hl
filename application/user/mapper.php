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

    public function update(data_user $user){
        $sql = new sql();
        $sql->query('UPDATE `users` SET `firstname` = :firstname, `lastname` = :lastname,
                    `midlename` = :middlename, `status` = :status, `password` = :password, `username` = :login WHERE `id` = :id');
        $user->verify('firstname');
        $user->verify('middlename');
        $user->verify('lastname');
        $user->verify('status');
        $user->verify('login');
        $user->verify('id');

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