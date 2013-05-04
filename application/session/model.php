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
            model_session::set_user($user);
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
}