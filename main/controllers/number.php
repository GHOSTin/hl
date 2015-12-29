<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class number{

  public function generate_password(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->generate_password($app['salt'], $app['email_for_reply'], $app['Swift_Message'], $app['mailer'], $app['site_url']);
  }

  public function edit_event(Application $app, Request $request, $event_id){
    list($number_id, $event_id, $time) = explode('-', $event_id);
    $n2e = $app['main\models\factory']->get_number_model($number_id)
                                      ->edit_event($event_id, $time, $request->get('description'),  $request->get('files'));
    return $app->json(['n2e' => $n2e]);
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

  public function get_event(Application $app, $id){
    list($number_id, $event_id, $time) = explode('-', $id);
    $n2e = $app['main\models\factory']->get_number_model($number_id)
                                      ->get_event($event_id, $time);
    return $app->json(['event' => $n2e]);
  }

  public function get_dialog_edit_event(Application $app, $event_id){
    list($number_id, $event_id, $time) = explode('-', $event_id);
    return $app['main\models\factory']->get_number_model($number_id)
                                      ->get_dialog_edit_event($event_id, $time);
  }

  public function get_number_json(Application $app, $id){
    $response = $app['main\models\factory']->get_number_model($id)
                                           ->get_number_json();
    return $app->json($response);
  }

  public function history(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->history();
  }

  public function meterages(Application $app, $id){
    return $app['main\models\factory']->get_number_model($id)
                                      ->meterages();
  }

  public function exclude_event(Application $app, $id){
    list($number_id, $event_id, $time) = explode('-', $id);
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