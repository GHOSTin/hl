<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use domain\user;
use League\Flysystem\Filesystem;

class logs{

  private $user;
  private $twig;

  public function __construct(Twig_Environment $twig, user $user, Filesystem $filesystem){
    if(!($user->check_access('system/general_access') && $user->check_access('system/logs')))
      throw new RuntimeException('ACCESS DENIED');
    $this->twig = $twig;
    $this->user = $user;
    $this->filesystem = $filesystem;
  }

  public function client(){
    return $this->twig->render('logs/client.tpl',
                                [
                                  'user' => $this->user,
                                  'rows' => $this->get_rows()
                                ]
                              );
  }

  public function default_page(){
    return $this->twig->render('logs/default_page.tpl', ['user' => $this->user]);
  }

  public function get_rows(){
    $stream = $this->filesystem->readStream('auth.log');
    while($row = json_decode(fgets($stream))){
      yield $row;
    }
    fclose($stream);
  }
}