<?php
class controller_user{

	static $name = 'Пользователи';

    public static function private_add_profile(model_request $request){
        model_profile::add_profile($request->take_get('company_id'),
                $request->take_get('user_id'), $request->take_get('profile'));
        return ['companies' => model_profile::get_companies($request->take_get('user_id'))];
    }

    public static function private_add_user(model_request $request){
        return ['group' => (new model_group(model_session::get_company()))
                                    ->add_user($request->take_get('group_id'),
                                            $request->take_get('user_id'))];
    }

    public static function private_create_group(model_request $request){
        $model = new model_group(model_session::get_company());
        $group = $model->create_group($request->take_get('name'), 'true');
        $letter_group = mb_strtolower(mb_substr($group->get_name(), 0 ,1, 'utf-8'), 'utf-8');
        $letters = [];
        $groups = $model->get_groups();
        if(!empty($groups))
            foreach($groups as $group){
                $letter = mb_strtolower(mb_substr($group->get_name(), 0 ,1, 'utf-8'), 'utf-8');
                if($letter === $letter_group)
                    $letters[] = $group;
            }
        return ['groups' => $letters];
    }

    public static function private_create_user(model_request $request){
        if($request->take_get('password') !== $request->take_get('confirm'))
            throw new e_model('Пароль и подтверждение не равны.');
        if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $request->take_get('password')))
            throw new e_model('Пароль не удовлетворяет a-zA-Z0-9 или меньше 8 символов.');
        $user = (new model_user)->create_user($request->take_get('lastname'),
                                        $request->take_get('firstname'),
                                        $request->take_get('middlename'),
                                        $request->take_get('login'),
                                        $request->take_get('password'), 'true');
        $letter_user = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
        $letter_users = [];
        $users = (new model_user)->get_users();
        if(!empty($users))
            foreach($users as $user){
                $letter = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
                if($letter === $letter_user)
                    $letter_users[] = $user;
            }
        return ['users' => $letter_users];
    }

    public static function private_delete_profile(model_request $request){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        model_profile::delete_profile($company, $user, $_GET['profile']);
        return ['user' => $user, 'company' => $company, 'profile_name' => $_GET['profile']];
    }

    public static function private_exclude_user(model_request $request){
        return ['group' => (new model_group(model_session::get_company()))
                            ->exclude_user($request->take_get('group_id'),
                                            $request->take_get('user_id'))];
    }

    public static function private_get_company_content(model_request $request){
        $user = new data_user();
        $user->set_id($request->take_get('user_id'));
        $company = new data_company();
        $company->set_id($request->take_get('user_id'));
        $profiles = (new model_user2profile($company, $user))->get_profiles();
        return ['user' => $user, 'company' => $company, 'profiles' => $profiles];
    }

    public static function private_get_profile_content(model_request $request){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        return ['user' => $user, 'company' => $company, 'profile_name' => $_GET['profile'],
                'profile' => model_profile::get_profile($company, $user, $_GET['profile'])];
    }

    public static function private_get_restriction_content(model_request $request){
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

    public static function private_get_dialog_create_group(model_request $request){
        return true;
    }

    public static function private_get_dialog_create_user(model_request $request){
        return true;
    }

    public static function private_get_dialog_add_profile(model_request $request){
        return ['companies' => model_company::get_companies(new data_company())];
    }

    public static function private_get_dialog_add_user(model_request $request){
        return ['users' => (new model_user())->get_users()];
    }

    public static function private_get_dialog_delete_profile(model_request $request){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        return ['user' => $user, 'company' => $company, 'profile_name' => $_GET['profile']];
    }

    public static function private_get_dialog_edit_group_name(model_request $request){
        return ['group' => (new model_group(model_session::get_company()))
                                        ->get_group($request->take_get('id'))];
    }

    public static function private_get_dialog_edit_fio(model_request $request){
        return ['user' => (new model_user)->get_user($request->take_get('id'))];
    }

    public static function private_get_dialog_edit_password(model_request $request){
        return ['user' => (new model_user)->get_user($request->take_get('id'))];
    }

    public static function private_get_dialog_edit_user_status(model_request $request){
        return ['user' => (new model_user)->get_user($request->take_get('id'))];
    }

    public static function private_get_dialog_exclude_user(model_request $request){
        return ['user' => (new model_user)->get_user($request->take_get('user_id')),
                'group' => (new model_group(model_session::get_company()))
                            ->get_group($request->take_get('group_id'))];
    }

    public static function private_get_group_letters(model_request $request){
        $letters = [];
        $groups = (new model_group(model_session::get_company()))->get_groups();
        if(!empty($groups))
            foreach($groups as $group){
                $letter = mb_strtolower(mb_substr($group->get_name(), 0 ,1, 'utf-8'), 'utf-8');
                $letters[$letter]++;
            }
        return ['letters' => $letters];
    }

    public static function private_get_group_profile(model_request $request){
        return ['group' => (new model_group(
            model_session::get_company()))->get_group($request->take_get('id'))];
    }

    public static function private_get_group_users(model_request $request){
        $group = new data_group();
        $group->set_id($request->take_get('id'));
        (new model_group(model_session::get_company()))->init_users($group);
        return ['group' => $group];
    }

    public static function private_get_dialog_edit_login(model_request $request){
        return ['user' => (new model_user)->get_user($request->take_get('id'))];
    }

	public static function private_show_default_page(model_request $request){
        $letters = [];
        $users = (new model_user())->get_users();
        if(!empty($users))
            foreach($users as $user){
                $letter = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
                $letters[$letter]++;
            }
        return ['letters' => $letters];
	}

    public static function private_get_group_letter(model_request $request){
        $letters = [];
        $groups = (new model_group(model_session::get_company()))->get_groups();
        if(!empty($groups))
            foreach($groups as $group){
                $letter = mb_strtolower(mb_substr($group->get_name(), 0 ,1, 'utf-8'), 'utf-8');
                if($letter === $request->take_get('letter'))
                    $letters[] = $group;
            }
        return ['groups' => $letters];
    }

    public static function private_get_user_letter(model_request $request){
        $letter_users = [];
        $users = (new model_user)->get_users();
        if(!empty($users))
            foreach($users as $user){
                $letter = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
                if($letter === $request->take_get('letter'))
                    $letter_users[] = $user;
            }
        return ['users' => $letter_users];
    }

    public static function private_get_group_content(model_request $request){
        return ['group' => (new model_group(model_session::get_company()))->get_group($request->take_get('id'))];
    }

    public static function private_get_user_content(model_request $request){
        return ['user' => (new model_user)->get_user($request->take_get('id'))];
    }

    public static function private_get_user_information(model_request $request){
        return ['user' => (new model_user)->get_user($_GET['id'])];
    }

    public static function private_get_user_profiles(model_request $request){
        $user = (new model_user)->get_user($request->take_get('id'));
        return ['user' => $user,
                'companies' => model_profile::get_companies($request->take_get('id'))];
    }

    public static function private_get_user_letters(model_request $request){
        $letters = [];
        $users = (new model_user())->get_users();
        if(!empty($users))
            foreach($users as $user){
                $letter = mb_strtolower(mb_substr($user->get_lastname(), 0 ,1, 'utf-8'), 'utf-8');
                $letters[$letter]++;
            }
        return ['letters' => $letters];
    }

    public static function private_update_group_name(model_request $request){
        return ['group' => (new model_group(model_session::get_company()))
        ->update_name($request->take_get('id'), $request->take_get('name'))];
    }

    public static function private_update_fio(model_request $request){
        return ['user' => (new model_user)->update_fio($request->take_get('id'), 
                $request->take_get('lastname'), $request->take_get('firstname'),
                $request->take_get('middlename'))];
    }

    public static function private_update_password(model_request $request){
        if($request->take_get('password') !== $request->take_get('confirm'))
            throw new e_model('Пароль и подтверждение не идентичны.');
        return ['user' => (new model_user)->update_password($request->take_get('id'),
                                                $request->take_get('password'))];
    }

    public static function private_update_rule(model_request $request){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        return ['user' => $user, 'company' => $company, 'profile_name' => $_GET['profile'], 'rule' => $_GET['rule'],
                'status' => model_profile::update_rule($company, $user, $_GET['profile'], $_GET['rule'])];
    }

    public static function private_update_restriction(model_request $request){
        $company = new data_company();
        $company->id = $_GET['company_id'];
        $company->verify('id');
        $user = new data_user();
        $user->id = $_GET['user_id'];
        $user->verify('id');
        return ['user' => $user, 'company' => $company, 'profile_name' => $_GET['profile'], 'restriction_name' => $_GET['restriction'], 'item' => $_GET['item'],
                'status' => model_profile::update_restriction($company, $user, $_GET['profile'], $_GET['restriction'], $_GET['item'])];
    }

    public static function private_update_login(model_request $request){
        return ['user' => (new model_user)->update_login($request->take_get('id'), $request->take_get('login'))];
    }

    public static function private_update_user_status(model_request $request){
        return ['user' => (new model_user)->update_user_status($request->take_get('id'))];
    }
}