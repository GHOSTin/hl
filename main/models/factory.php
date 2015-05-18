<?php namespace main\models;

class factory extends model{

  public function get_number_model($id){
    return new number5($this->app, $this->twig, $this->em, $this->user, $id);
  }

  public function get_reports_model(){
    return new reports($this->app, $this->twig, $this->em, $this->user);
  }

  public function get_report_queries_model(){
    return new report_queries($this->app, $this->twig, $this->em, $this->user, $this->app['session']);
  }
}