<?php

class factory_metrics {

  public function build($data){
    $metrics = new data_metrics();
    $metrics->set_id($data['id']);
    $metrics->set_address($data['address']);
    $metrics->set_metrics($data['metrics']);
    return $metrics;
  }
} 