<?php namespace domain;

use DateTime;
use JsonSerializable;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity(repositoryClass="domain\repositories\number2event")
*/
class number2event implements JsonSerializable{

  /**
  * @Id
  * @Column
  */
  private $id;

  /**
  * @Column(type="integer")
  */
  private $time;

  /**
  * @ManyToOne(targetEntity="domain\number")
  */
  private $number;

  /**
  * @ManyToOne(targetEntity="domain\event")
  */
  private $event;

  /**
  * @Column
  */
  private $description;

  /**
   * @ManyToMany(targetEntity="domain\file")
   * @JoinTable(name="number2event2file",
   *             joinColumns={@JoinColumn(name="number2event_id", referencedColumnName="id")},
   *             inverseJoinColumns={@JoinColumn(name="path", referencedColumnName="path", unique=true)}
   *           )
   */
  private $files;

  public function __construct(number $number, event $event, $date, $description = ''){
    $time = DateTime::createFromFormat('H:i d.m.Y', '12:00 '.$date);
    $this->time = $time->getTimeStamp();
    $this->files = new ArrayCollection();
    $this->description = $description;
    $this->id = $number->get_id().'-'.$event->get_id().'-'.$this->time;
    $this->event = $event;
    $this->number = $number;
  }

  public function add_file(file $file){
    $this->files->add($file);
  }

  public function get_time(){
    return $this->time;
  }

  public function get_description(){
    return $this->description;
  }

  public function get_name(){
    return $this->event->get_name();
  }

  public function get_id(){
    return $this->id;
  }

  public function get_files(){
    return $this->files;
  }

  public function JsonSerialize(){
    return [
             'id' => $this->id,
             'time' => $this->time,
             'event' => [
                          'id' => $this->event->get_id(),
                          'name' => $this->event->get_name()
                        ],
              'number' => [
                            'id' => $this->number->get_id(),
                            'number' => $this->number->get_number()
                          ],
             'description' => $this->description
           ];
  }

  public function set_description($description){
    $this->description = $description;
  }

}
