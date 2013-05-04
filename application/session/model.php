<?php
class model_session{
    private $user;

    public function get_user(){
        return $this->user;
    }
    public function verify_user(){
        session_start();
        $user = $_SESSION['user'];
        if($user instanceof data_user){
            self::set_user($user);
            return $user;
        }else{
            session_destroy();
            return false;
        }
    }

    public function set_user(data_user $user){
        if(!isset($this->user))
            $this->user = $user;
        else
            throw new exception('Нельзя дважды определить пользователя.');
    }
    /*if($method === 'show_default_page')
                $c_data['componentName'] = $component;
            $c_data['anonymous'] = true;*/
            // нужно вынести это кусок -->
            /*if($_SESSION['user'] instanceof data_user){
                model_profile::get_user_profiles();
                $access = (model_profile::check_general_access($controller, $component));
                if($access !== true){
                    $controller = 'controller_error';
                    $view = 'view_error';
                    $prefix = 'private_';
                    $method = 'get_access_denied_message';
                }
                model_menu::build_hot_menu($component, $controller);
                $c_data['menu'] = view_menu::build_horizontal_menu(['menu' => $_SESSION['menu'], 'hot_menu' => $_SESSION['hot_menu']]);
                $c_data['anonymous'] = false;
            }*/
            // <--
}