<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class number{

  public function add_event(Request $request, Application $app, $number_id){
    return $app['main\models\factory']->get_number_model($number_id)
                                      ->add_event(
                                                          $request->get('event'),
                                                          $request->get('date'),
                                                          $request->get('comment')
                                                        );
  }

  public function generate_password(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->generate_password($app['salt'], $app['email_for_reply'], $app['Swift_Message'], $app['mailer']);
  }

  public function get_dialog_add_event(Application $app, $number_id){
    return $app['main\models\factory']->get_number_model($number_id)
                                      ->get_dialog_add_event();
  }

  public function edit_event(Application $app, Request $request, $number_id, $event_id, $time){
    return $app['main\models\factory']->get_number_model($number_id)
                                      ->edit_event($event_id, $time, $request->get('description'));
  }

  public function get_dialog_contacts(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->get_dialog_contacts();
  }

  public function get_dialog_generate_password(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->get_dialog_generate_password();
  }

  public function get_dialog_exclude_event(Application $app, $number_id, $event_id, $time){
    return $app['main\models\factory']->get_number_model($number_id)
                                      ->get_dialog_exclude_event($event_id, $time);
  }

  public function get_dialog_edit_event(Application $app, $number_id, $event_id, $time){
    return $app['main\models\factory']->get_number_model($number_id)
                                      ->get_dialog_edit_event($event_id, $time);
  }

  public function history(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->history();
  }

  public function meterages(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->meterages();
  }

  public function exclude_event(Application $app, $number_id, $event_id, $time){
    return $app['main\models\factory']->get_number_model($number_id)
                                      ->exclude_event($event_id, $time);
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