<?php
class controller_user{

	static $name = 'Пользователи';

    public static function private_add_profile(){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        model_profile::add_profile($company, $user, $_GET['profile']);
        return ['users' => model_user::get_users($user),
                'companies' => model_profile::get_companies($user)];
    }

    public static function private_add_user(){
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $group = new data_group();
        $group->id = $_GET['group_id'];
        $company = model_session::get_company();
        model_group::add_user($company, $group, $user);
        return ['group' => $group,
                'users' => model_group::get_users(model_session::get_company(), $group)];
        
    }

    public static function private_create_group(){
        $group = new data_group();
        $group->name = $_GET['name'];
        $company = model_session::get_company();
        $group = model_group::create_group($company, $group);
        $letter_group = mb_strtolower(mb_substr($group->name, 0 ,1, 'utf-8'), 'utf-8');
        $letters = [];
        $groups = model_group::get_groups(model_session::get_company(), new data_group());
        if(!empty($groups))
            foreach($groups as $group){
                $letter = mb_strtolower(mb_substr($group->name, 0 ,1, 'utf-8'), 'utf-8');
                if($letter === $letter_group)
                    $letters[] = $group;
            }
        return ['groups' => $letters];
    }

    public static function private_create_user(){
        if($_GET['password'] !== $_GET['confirm'])
            throw new e_model('Пароль и подтверждение не равны.');
        $user = new data_user();
        $user->lastname = $_GET['lastname'];
        $user->firstname = $_GET['firstname'];
        $user->middlename = $_GET['middlename'];
        $user->login = $_GET['login'];
        $user->password = $_GET['password'];
        $user->status = 'true';
        if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $user->password))
            throw new e_model('Пароль не удовлетворяет a-zA-Z0-9 или меньше 8 символов.');
        $user = (new model_user)->create_user($user);
        $letter_user = mb_strtolower(mb_substr($user->lastname, 0 ,1, 'utf-8'), 'utf-8');
        $letter_users = [];
        $users = model_user::get_users(new data_user());
        if(!empty($users))
            foreach($users as $user){
                $letter = mb_strtolower(mb_substr($user->lastname, 0 ,1, 'utf-8'), 'utf-8');
                if($letter === $letter_user)
                    $letter_users[] = $user;
            }
        return ['users' => $letter_users];
    }

    public static function private_delete_profile(){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        model_profile::delete_profile($company, $user, $_GET['profile']);
        return ['user' => $user, 'company' => $company, 'profile_name' => $_GET['profile']];
    }

    public static function private_exclude_user(){
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $group = new data_group();
        $group->id = $_GET['group_id'];
        $company = model_session::get_company();
        model_group::exclude_user($company, $group, $user);
        return ['group' => $group,
                'users' => model_group::get_users($company, $group)];
    }

    public static function private_get_company_content(){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        return ['user' => $user, 'company' => $company,
                'profiles' => model_profile::get_profiles($company, $user)];
    }

    public static function private_get_profile_content(){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        return ['user' => $user, 'company' => $company, 'profile_name' => $_GET['profile'],
                'profile' => model_profile::get_profile($company, $user, $_GET['profile'])];
    }

    public static function private_get_restriction_content(){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        $profile = $_GET['profile'];
        $restriction = $_GET['restriction'];
        if($profile !== 'query')
            throw new e_model('Нет ограничения для профиля.');
        if(!in_array($restriction, ['departments', 'worktypes']))
            throw new e_model('Нет ограничения для профиля.');
        if($restriction === 'departments')
            $items = model_department::get_departments($company, new data_department());
        if($restriction === 'worktypes')
            $items = model_query_work_type::get_query_work_types($company, new data_query_work_type());
        return ['user' => $user, 'company' => $company, 'profile_name' => $_GET['profile'],
                'restriction_name' => $restriction, 'items' => $items,
                'profile' => model_profile::get_profile($company, $user, $_GET['profile'])];
    }

    public static function private_get_dialog_create_group(){
        return true;
    }

    public static function private_get_dialog_create_user(){
        return true;
    }

    public static function private_get_dialog_add_profile(){
        $user = new data_user();
        $user->id = $_GET['id'];
        $user->verify('id');
        return ['user' => $user, 'companies' => model_company::get_companies(new data_company())];
    }

    public static function private_get_dialog_add_user(){
        $group = new data_group();
        $group->id = $_GET['id'];
        return ['users' => model_user::get_users(new data_user()),
                'group' => $group];
    }

    public static function private_get_dialog_delete_profile(){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        return ['user' => $user, 'company' => $company, 'profile_name' => $_GET['profile']];
    }

    public static function private_get_dialog_edit_group_name(){
        $group = new data_group();
        $group->id = $_GET['id'];
        $group->verify('id');
        return ['groups' => model_group::get_groups(model_session::get_company(), $group)[0]];
    }

    public static function private_get_dialog_edit_fio(){
        return ['user' => (new model_user)->get_user($_GET['id'])];
    }

    public static function private_get_dialog_edit_password(){
        return ['user' => (new model_user)->get_user($_GET['id'])];
    }

    public static function private_get_dialog_edit_user_status(){
        return ['user' => (new model_user)->get_user($_GET['id'])];
    }

    public static function private_get_dialog_exclude_user(){
        $group = new data_group();
        $group->id = $_GET['group_id'];
        $group->verify('id');
        return ['user' => (new model_user)->get_user($_GET['user_id']),
                'group' => model_group::get_groups(model_session::get_company(), $group)[0]];
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
        return ['group' => $group,
                'users' => model_group::get_users(model_session::get_company(), $group)];
    }

    public static function private_get_dialog_edit_login(){
        return ['user' => (new model_user)->get_user($_GET['id'])];
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

    public static function private_get_user_information(){
        return ['user' => (new model_user)->get_user($_GET['id'])];
    }

    public static function private_get_user_profiles(){
        $user = new data_user();
        $user->id = $_GET['id'];
        $user->verify('id');
        return ['users' => model_user::get_users($user),
                'companies' => model_profile::get_companies($user)];
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

    public static function private_update_group_name(){
        $group = new data_group();
        $group->id = $_GET['id'];
        return ['group' => model_group::update_name(model_session::get_company(), $group, $_GET['name'])];
    }

    public static function private_update_fio(){
        return ['user' => (new model_user)->update_fio($_GET['id'], $_GET['lastname'], $_GET['firstname'], $_GET['middlename'])];
    }

    public static function private_update_password(){
        if($_GET['password'] !== $_GET['confirm'])
            throw new e_model('Пароль и подтверждение не идентичны.');
        return ['user' => (new model_user)->update_password($_GET['id'], $_GET['password'])];
    }

    public static function private_update_rule(){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        return ['user' => $user, 'company' => $company, 'profile_name' => $_GET['profile'], 'rule' => $_GET['rule'],
                'status' => model_profile::update_rule($company, $user, $_GET['profile'], $_GET['rule'])];
    }

    public static function private_update_restriction(){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        return ['user' => $user, 'company' => $company, 'profile_name' => $_GET['profile'], 'restriction_name' => $_GET['restriction'], 'item' => $_GET['item'],
                'status' => model_profile::update_restriction($company, $user, $_GET['profile'], $_GET['restriction'], $_GET['item'])];
    }

    public static function private_update_login(){
        return ['user' => (new model_user)->update_login($_GET['id'], $_GET['login'])];
    }

    public static function private_update_user_status(){
        return ['user' => (new model_user)->update_user_status($_GET['id'])];
    }
}