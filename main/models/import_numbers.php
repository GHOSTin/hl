<?php namespace main\models;

use RuntimeException;
use Silex\Application;
use domain\street;
use domain\house;
use domain\flat;
use domain\number;

class import_numbers{

	private $app;
  private $fond;

	public function __construct(Application $app){
		$this->app = $app;
    if(isset($_SESSION['import']))
      $this->fond = $_SESSION['import'];
	}

	public function load_file($hndl){
    $this->fond = [];
    while($row = fgetcsv($hndl, 0, ';')){
      $this->analize_row($row);
    }
    $_SESSION['import'] = $this->fond;
	}

  public function analize_row(array $row){
    list($city, $street, $house, $flat, $number, $fio) = $row;
    $street = trim($street);
    $house = trim($house);
    $flat = trim($flat);
    $number = trim($number);
    $fio = trim($fio);
    if(!array_key_exists($street, $this->fond))
      $this->fond[$street] = [];
    if(!array_key_exists($house, $this->fond[$street]))
      $this->fond[$street][$house] = [];
    if(!array_key_exists($flat, $this->fond[$street][$house]))
      $this->fond[$street][$house][$flat][$number] = [$fio];
  }

  public function get_flats(){
    $new = [];
    $streets = array_keys($this->fond);
    foreach($streets as $street_name){
      $street = $this->app['em']->getRepository('domain\street')
                                ->findOneByName($street_name);
      if(is_null($street))
        throw new RuntimeException();
      $houses = array_keys($this->fond[$street_name]);
      foreach($houses as $house_number){
        $house = $this->app['em']->getRepository('domain\house')
                                 ->findOneBy([
                                              'street' => $street,
                                              'number' => $house_number
                                             ]);
        if(is_null($house))
          throw new RuntimeException();
        foreach(array_keys($this->fond[$street_name][$house_number]) as $flat_number){
          $flat = $this->app['em']->getRepository('domain\flat')
                                  ->findOneBy([
                                               'house' => $house,
                                               'number' => $flat_number
                                              ]);
          if(is_null($flat))
            $new[] = $street_name.' '.$house_number.' '.$flat_number;
        }
      }
    }
    return $new;
  }

  public function get_houses(){
    $new = [];
    $streets = array_keys($this->fond);
    foreach($streets as $street_name){
      $street = $this->app['em']->getRepository('domain\street')
                                ->findOneByName($street_name);
      if(is_null($street))
        throw new RuntimeException();
      $houses = array_keys($this->fond[$street_name]);
      foreach($houses as $house_number){
        $house = $this->app['em']->getRepository('domain\house')
                                 ->findOneBy([
                                              'street' => $street,
                                              'number' => $house_number
                                             ]);
        if(is_null($house)){
          $new[] = $street_name.' '.$house_number;
        }
      }
    }
    return $new;
  }

  public function get_numbers(){
    $new = [];
    $streets = array_keys($this->fond);
    foreach($streets as $street_name){
      $street = $this->app['em']->getRepository('domain\street')
                                ->findOneByName($street_name);
      if(is_null($street))
        throw new RuntimeException();
      $houses = array_keys($this->fond[$street_name]);
      foreach($houses as $house_number){
        $house = $this->app['em']->getRepository('domain\house')
                                 ->findOneBy([
                                              'street' => $street,
                                              'number' => $house_number
                                             ]);
        if(is_null($house))
          throw new RuntimeException();
        foreach(array_keys($this->fond[$street_name][$house_number]) as $flat_number){
          $flat = $this->app['em']->getRepository('domain\flat')
                                   ->findOneBy([
                                                'house' => $house,
                                                'number' => $flat_number
                                               ]);
          if(is_null($flat))
            throw new RuntimeException();
          foreach(array_keys($this->fond[$street_name][$house_number][$flat_number]) as $number_number){
            $number = $this->app['em']->getRepository('domain\number')
                                     ->findOneBy([
                                                  'flat' => $flat,
                                                  'number' => $number_number
                                                 ]);
            if(is_null($number))
              $new[] = $street_name.' '.$house_number.' кв.'.$flat_number.' №'.$number_number;
          }
        }
      }
    }
    return $new;
  }

  public function get_streets(){
    $new = [];
    $streets = array_keys($this->fond);
    foreach($streets as $name){
      $street = $this->app['em']->getRepository('domain\street')
                                ->findOneByName($name);
      if(is_null($street))
        $new[] = $name;
    }
    return $new;
  }

  public function load_flats(){
    $streets = array_keys($this->fond);
    foreach($streets as $street_name){
      $street = $this->app['em']->getRepository('domain\street')
                                ->findOneByName($street_name);
      if(is_null($street))
        throw new RuntimeException();
      $houses = array_keys($this->fond[$street_name]);
      foreach($houses as $house_number){
        $house = $this->app['em']->getRepository('domain\house')
                                 ->findOneBy([
                                              'street' => $street,
                                              'number' => $house_number
                                             ]);
        if(is_null($house))
          throw new RuntimeException();
        foreach(array_keys($this->fond[$street_name][$house_number]) as $flat_number){
          $flat = $this->app['em']->getRepository('domain\flat')
                                   ->findOneBy([
                                                'house' => $house,
                                                'number' => $flat_number
                                               ]);
          if(is_null($flat)){
            $flat = flat::new_instance($house, $flat_number);
            $this->app['em']->persist($flat);
          }
        }
      }
    }
    $this->app['em']->flush();
  }

  public function load_houses(){
    $department = $this->app['em']->getRepository('domain\department')
                                  ->findOneById(1);
    $streets = array_keys($this->fond);
    foreach($streets as $street_name){
      $street = $this->app['em']->getRepository('domain\street')
                                ->findOneByName($street_name);
      if(is_null($street))
        throw new RuntimeException();
      $houses = array_keys($this->fond[$street_name]);
      foreach($houses as $house_number){
        $house = $this->app['em']->getRepository('domain\house')
                                 ->findOneBy([
                                              'street' => $street,
                                              'number' => $house_number
                                             ]);
        if(is_null($house)){
          $house = house::new_instance($department, $street, $house_number);
          $this->app['em']->persist($house);
        }
      }
    }
    $this->app['em']->flush();
  }

  public function load_numbers(){
    $streets = array_keys($this->fond);
    foreach($streets as $street_name){
      $street = $this->app['em']->getRepository('domain\street')
                                ->findOneByName($street_name);
      if(is_null($street))
        throw new RuntimeException();
      $houses = array_keys($this->fond[$street_name]);
      foreach($houses as $house_number){
        $house = $this->app['em']->getRepository('domain\house')
                                 ->findOneBy([
                                              'street' => $street,
                                              'number' => $house_number
                                             ]);
        if(is_null($house))
          throw new RuntimeException();
        foreach(array_keys($this->fond[$street_name][$house_number]) as $flat_number){
          $flat = $this->app['em']->getRepository('domain\flat')
                                   ->findOneBy([
                                                'house' => $house,
                                                'number' => $flat_number
                                               ]);
          if(is_null($flat))
            throw new RuntimeException();
          foreach(array_keys($this->fond[$street_name][$house_number][$flat_number]) as $number_number){
            $number = $this->app['em']->getRepository('domain\number')
                                      ->findOneBy([
                                                   'flat' => $flat,
                                                   'number' => $number_number
                                                  ]);
            if(is_null($number)){
              list($fio) = $this->fond[$street_name][$house_number][$flat_number][$number_number];
              $number = number::new_instance($house, $flat, $number_number, $fio);
              $this->app['em']->persist($number);
            }
          }
        }
      }
    }
    $this->app['em']->flush();
    $this->fond = [];
  }

  public function load_streets(){
    $streets = array_keys($this->fond);
    foreach($streets as $street_name){
      $street = $this->app['em']->getRepository('domain\street')
                                ->findOneByName($street_name);
      if(is_null($street)){
        $street = street::new_instance($street_name);
        $this->app['em']->persist($street);
      }
    }
    $this->app['em']->flush();
  }
}