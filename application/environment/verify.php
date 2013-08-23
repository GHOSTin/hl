<?php
class verify_environment{

    public function verify_unix_time($time){
        if($time < 1)
            throw new e_model('Время задано не верно.');
    }
}