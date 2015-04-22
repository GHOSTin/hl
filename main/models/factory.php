<?php namespace main\models;

class factory extends model{

  public function get_number_model($id){
    return new number5($this->app, $this->twig, $this->em, $this->user, $id);
  }
}