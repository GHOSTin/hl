<?php
class controller_user{

	static $name = 'Пользователи';

	public static function private_show_default_page(){
        $letters = [];
        $users = model_user::get_users(new data_user());
        if(!empty($users))
            foreach($users as $user){
                $letter = mb_strtolower(mb_substr($user->lastname, 0 ,1, 'utf-8'), 'utf-8');
                $letters[$letter]++;
            }
        ksort($letters);
        return ['letters' => $letters];
	}

    public static function private_get_user_letter(){
        $letter_users = [];
        $users = model_user::get_users(new data_user());
        if(!empty($users))
            foreach($users as $user){
                $letter = mb_strtolower(mb_substr($user->lastname, 0 ,1, 'utf-8'), 'utf-8');
                if($letter === $_GET['letter'])
                    $letter_users[] = $user;
            }
        return ['users' => $letter_users];
    }
}