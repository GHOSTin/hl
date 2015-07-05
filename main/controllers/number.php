<?php namespace main\controllers;

use Silex\Application;

class number{

  public function generate_password(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->generate_password($app['salt'], $app['email_for_reply'], $app['Swift_Message'], $app['mailer']);
  }

  public function get_dialog_generate_password(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->get_dialog_generate_password();
  }
}