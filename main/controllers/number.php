<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class number{

  public function generate_password(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->generate_password($app['salt'], $app['email_for_reply'], $app['Swift_Message'], $app['mailer']);
  }

  public function get_dialog_contacts(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->get_dialog_contacts();
  }

  public function get_dialog_generate_password(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->get_dialog_generate_password();
  }

  public function history(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->history();
  }

  public function meterages(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->meterages();
  }

  public function update_contacts(Application $app, Request $request, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->update_contacts(
                                          $request->get('fio'),
                                          $request->get('telephone'),
                                          $request->get('cellphone'),
                                          $request->get('email')
                                      );
  }
}