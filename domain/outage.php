<?php namespace domain;

use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity(repositoryClass="domain\repositories\outage")
*/
class outage{

  /**
  * @Id
  * @Column(type="integer")
  */
  private $id;

  /**
  * @Column(type="integer")
  */
  private $begin;

  /**
  * @Column(type="integer")
  */
  private $target;

  /**
  * @Column
  */
  private $description;

  /**
  * @ManyToMany(targetEntity="domain\house", inversedBy="outages")
  * @JoinTable(name="outage2house")
  */
  private $houses;

  /**
  * @ManyToMany(targetEntity="domain\user", inversedBy="outages")
  * @JoinTable(name="outage2performer")
  */
  private $performers;

  /**
  * @ManyToOne(targetEntity="domain\user")
  */
  private $user;

  /**
  * @ManyToOne(targetEntity="domain\workgroup")
  */
  private $category;

  private static $i = 1000;

  public function __construct(){
    $this->houses = new ArrayCollection();
    $this->performers = new ArrayCollection();
  }

  public function get_id(){
    return $this->id;
  }

  public function get_target(){
    return $this->target;
  }

  public function get_begin(){
    return $this->begin;
  }

  public function get_category(){
    return $this->category;
  }

  public function get_user(){
    return $this->user;
  }

  public function get_description(){
    return $this->description;
  }

  public function get_houses(){
    return $this->houses;
  }

  public function get_performers(){
    return $this->performers;
  }

  public function add_house(house $house){
    $this->houses->add($house);
  }

  public function add_performer(user $user){
    $this->performers->add($user);
  }

  public function update($begin, $target, workgroup $category, user $user, array $houses, array $performers, $description){
    $this->begin = $begin;
    $this->target = $target;
    $this->category = $category;
    $this->user = $user;
    $this->description = $description;
    $this->houses->clear();
    $this->performers->clear();
    foreach($houses as $house)
      $this->add_house($house);
    foreach($performers as $performer)
      $this->add_performer($performer);
  }

  public static function new_instance($begin, $target, workgroup $category, user $user, array $houses, array $performers, $description){
    $outage = new self();
    $outage->id = time() + self::$i++;
    $outage->begin = $begin;
    $outage->target = $target;
    $outage->category = $category;
    $outage->user = $user;
    $outage->description = $description;
    foreach($houses as $house)
      $outage->add_house($house);
    foreach($performers as $performer)
      $outage->add_performer($performer);
    return $outage;
  }
}