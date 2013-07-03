<?php
class controller_user{

	static $name = 'Пользователи';

    public static function private_get_dialog_edit_group_name(){
        $group = new data_group();
        $group->id = $_GET['id'];
        $group->verify('id');
        return ['groups' => model_group::get_groups(model_session::get_company(), $group)];
    }

    public static function private_get_dialog_edit_fio(){
        $user = new data_user();
        $user->id = $_GET['id'];
        $user->verify('id');
        return ['users' => model_user::get_users($user)];
    }

    public static function private_get_dialog_edit_password(){
        $user = new data_user();
        $user->id = $_GET['id'];
        $user->verify('id');
        return ['users' => model_user::get_users($user)];
    }

    public static function private_get_group_letters(){
        $letters = [];
        $groups = model_group::get_groups(model_session::get_company(), new data_group());
        if(!empty($groups))
            foreach($groups as $group){
                $letter = mb_strtolower(mb_substr($group->name, 0 ,1, 'utf-8'), 'utf-8');
                $letters[$letter]++;
            }
        return ['letters' => $letters];
    }

    public static function private_get_group_profile(){
        $group = new data_group();
        $group->id = $_GET['id'];
        $group->verify('id');
        return ['groups' => model_group::get_groups(model_session::get_company(), $group)];
    }

    public static function private_get_group_users(){
        $group = new data_group();
        $group->id = $_GET['id'];
        $group->verify('id');
        return ['users' => model_group::get_users(model_session::get_company(), $group)];
    }

    public static function private_get_dialog_edit_login(){
        $user = new data_user();
        $user->id = $_GET['id'];
        $user->verify('id');
        return ['users' => model_user::get_users($user)];
    }

	public static function private_show_default_page(){
        $letters = [];
        $users = model_user::get_users(new data_user());
        if(!empty($users))
            foreach($users as $user){
                $letter = mb_strtolower(mb_substr($user->lastname, 0 ,1, 'utf-8'), 'utf-8');
                $letters[$letter]++;
            }
        return ['letters' => $letters];
	}

    public static function private_get_group_letter(){
        $letters = [];
        $groups = model_group::get_groups(model_session::get_company(), new data_group());
        if(!empty($groups))
            foreach($groups as $group){
                $letter = mb_strtolower(mb_substr($group->name, 0 ,1, 'utf-8'), 'utf-8');
                if($letter === $_GET['letter'])
                    $letters[] = $group;
            }
        return ['groups' => $letters];
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

    public static function private_get_group_content(){
        $group = new data_group();
        $group->id = $_GET['id'];
        $group->verify('id');
        return ['groups' => model_group::get_groups(model_session::get_company(), $group)];
    }

    public static function private_get_user_content(){
        $user = new data_user();
        $user->id = $_GET['id'];
        $user->verify('id');
        return ['users' => model_user::get_users($user)];
    }

    public static function private_get_user_letters(){
        $letters = [];
        $users = model_user::get_users(new data_user());
        if(!empty($users))
            foreach($users as $user){
                $letter = mb_strtolower(mb_substr($user->lastname, 0 ,1, 'utf-8'), 'utf-8');
                $letters[$letter]++;
            }
        return ['letters' => $letters];
    }

    public static function private_update_fio(){
        $user = new data_user();
        $user->id = $_GET['id'];
        return ['user' => model_user::update_fio($user, $_GET['lastname'], $_GET['firstname'], $_GET['middlename'])];
    }

    public static function private_update_password(){
        $user = new data_user();
        $user->id = $_GET['id'];
        return ['user' => model_user::update_password($user, $_GET['password'], $_GET['confirm'])];
    }

    public static function private_update_login(){
        $user = new data_user();
        $user->id = $_GET['id'];
        return ['user' => model_user::update_login($user, $_GET['login'])];
    }
}