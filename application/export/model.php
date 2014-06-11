<?php

class model_export {

  public function export_numbers($attachment = false){
    $filename = 'export_numbers.csv';
    @set_time_limit(0);
    if($attachment) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream; charset=utf-8');
      header( 'Content-Disposition: attachment;filename='.$filename);
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      $fp = tmpfile();
    } else {
      $fp = fopen($filename, 'w');
    }

    fputcsv($fp, array('Улица', 'Дом', 'Квартира', 'Лицевой счет', 'ФИО'));

    $data = di::get('mapper_export')->find_all(di::get('company'));

    while (list($key, $value) = each($data))
      fputcsv($fp, $value);

    readfile($fp);
    fclose($fp);
  }

} 