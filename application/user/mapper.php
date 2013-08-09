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

    private function is_data_user($user){
        if(!($user instanceof data_user))
            throw new e_model('Возвращен объект не является пользователем');
    }
}