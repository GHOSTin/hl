<?php namespace domain;

/**
* @Entity
* @Table(name="query2file")
*/
class query2file implements interfaces\file{

  /**
  * @Id
  * @ManyToOne(targetEntity="domain\query")
  */
  private $query;

  /**
  * @Id
  * @ManyToOne(targetEntity="domain\file", cascade="all")
  * @JoinColumn(name="path", referencedColumnName="path")
  */
  private $file;

  public function __construct(query $query, file $file){
    $this->query = $query;
    $this->file = $file;
  }

  public function get_name(){
    return $this->file->get_name();
  }

  public function get_path(){
    return $this->file->get_path();
  }

  public function get_query(){
    return $this->query;
  }

  public function get_time(){
    return $this->file->get_time();
  }

  public function get_user(){
    return $this->file->get_user();
  }
}