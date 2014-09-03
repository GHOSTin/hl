<?php
class controller_error{

  public static function error404(model_request $request){
    return null;
  }

  public static function private_show_dialog(model_request $request){
    return null;
  }

  public static function private_delete_error(model_request $request){
    $em = di::get('em');
    $error = $em->find('data_error', $request->GET('time'));
    if(is_null($error))
      throw new RuntimeException('Not entity');
    $em->remove($error);
    $em->flush();
  }

  public static function private_send_error(model_request $request){
    $em = di::get('em');
    $time = time();
    if(!is_null($em->find('data_error', $time)))
      throw new RuntimeException();
    $error = new data_error();
    $error->set_text($request->GET('text'));
    $error->set_time($time);
    $error->set_user(di::get('user'));
    $em->persist($error);
    $em->flush();
  }

  public static function private_show_default_page(model_request $request){
    return ['errors' => di::get('em')->getRepository('data_error')->findAll()];
  }
}