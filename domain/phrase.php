<?php namespace domain;

use JsonSerializable;

/**
* @Entity
*/
class phrase implements JsonSerializable{

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

  private static $i = 1000;

  public function __construct(){
    $this->id = time() + self::$i;
    self::$i++;
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

  public function JsonSerialize(){
    return [
             'text' => $this->text
           ];
  }
}