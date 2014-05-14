<?php

class model_export {

  public function export_numbers($attachment = false){
    $filename = 'export_numbers.csv';
    @set_time_limit(0);
    if($attachment) {
      header( 'Content-Type: text/csv; charset=utf-8' );
      header( 'Content-Disposition: attachment;filename='.$filename);
      $fp = fopen('php://output', 'w');
    } else {
      $fp = fopen($filename, 'w');
    }

    fputcsv($fp, array('Улица', 'Дом', 'Квартира', 'Лицевой счет', 'ФИО'));

    $data = di::get('mapper_export')->find_all(di::get('company'));

    while (list($key, $value) = each($data))
      fputcsv($fp, $value);

    fclose($fp);
  }

} 