<?php namespace domain;

/**
* @Entity
*/
class phrase{

  /**
  * @Id
  * @Column(type="integer")
  */
  private $id;

  /**
  * @Column(type="text")
  */
  private $text;

  /**
  * @ManyToOne(targetEntity="domain\workgroup",  inversedBy="phrases")
  */
  private $workgroup;

  public function __construct(){
    $this->id = time();
  }

  public function get_id(){
    return $this->id;
  }

  public function get_workgroup(){
    return $this->workgroup;
  }

  public function get_text(){
    return $this->text;
  }

  public function set_text($text){
    $this->text = $text;
  }

  public static function new_instance(workgroup $workgroup, $text){
    $phrase = new self();
    $phrase->workgroup = $workgroup;
    $phrase->text = $text;
    return $phrase;
  }
}