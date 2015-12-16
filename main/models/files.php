<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;
use domain\file;
use League\Flysystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class files{

  private $em;
  private $twig;
  private $user;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user, Filesystem $filesystem, Session $session, $root){
    $this->twig = $twig;
    $this->em = $em;
    $this->user = $user;
    $this->filesystem = $filesystem;
    $this->session = $session;
    $this->root = $root;
  }

  public function load(array $files){
    foreach($files as $file){
      if($file->isValid()) {
        $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $time = time();
        $name = sha1(implode('_', [$time, $this->session->getId(), rand(0, 100000)]));
        $path = date('Ymd').'/'.$name.'.'.$ext;
        $this->save_file($file->getRealPath(), $path);
        $file = new file($this->user, $path, $time, $file->getClientOriginalName());
        $f[] = $file;
        $this->em->persist($file);
      }
    }
    $this->em->flush();
    return $f;
  }

  public function save_file($real_path, $target_path){
    $stream = fopen($real_path, 'r+');
    $this->filesystem->writeStream($target_path, $stream);
    fclose($stream);
  }

  public function create_path($file){
    $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
    $path = date('Ymd').'/'.$name.'.'.$ext;
  }


  public function get_file($path){
    $file = $this->em->getRepository('domain\file')
                      ->find($path);
    if($file && $this->filesystem->has($file->get_path())){
      $response = new BinaryFileResponse($this->root.$file->get_path());
      $disposition = $response->headers->makeDisposition(
                                          ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                                          $file->get_name(),
                                          iconv('UTF-8', 'ASCII//TRANSLIT', $file->get_name())
                                         );
      $response->headers->set('Content-Disposition', $disposition);
      return $response;
    }else
      throw new NotFoundHttpException();
  }
}