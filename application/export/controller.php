<?php

class controller_export {
  public static function private_show_default_page(model_request $request){
    return true;
  }

  public static function private_get_dialog_export_numbers(
      model_request $request){
    return true;
  }

  public static function private_export_numbers(model_request $request){
    set_time_limit(0);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream; charset=utf-8');
    header( 'Content-Disposition: attachment;filename='.$filename);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    $fp = tmpfile();
    $uri = stream_get_meta_data($fp)['uri'];
    fputcsv($fp, ['Улица', 'Дом', 'Квартира', 'Лицевой счет', 'ФИО'], ';');
    $numbers = di::get('em')->getRepository('data_number')->findAll();
    foreach($numbers as $number){
      $value = [$number->get_flat()->get_house()->get_street()->get_name(),
                $number->get_flat()->get_house()->get_number(),
                $number->get_flat()->get_number(),
                $number->get_number(),
                $number->get_fio()];
      fputcsv($fp, $value, ';');
    }
    readfile($uri);
    fclose($fp);
    exit();
  }

}