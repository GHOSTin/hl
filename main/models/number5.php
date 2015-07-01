<?php namespace main\models;

use Silex\Application;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;
use domain\user;
use domain\number;
use RuntimeException;

class number5{

  private $app;
  private $em;
  private $twig;
  private $user;

  public function __construct(Application $app, Twig_Environment $twig, EntityManager $em, user $user, $id){
    $this->twig = $twig;
    $this->app = $app;
    $this->em = $em;
    $this->user = $user;
    if(!$this->user->check_access('numbers/general_access'))
      throw new RuntimeException();
    $this->number = $this->em->find('domain\number', $id);
    if(is_null($this->number))
      throw new RuntimeException();
  }

  public function generate_password(){
    if(!$this->user->check_access('numbers/generate_password'))
      throw new RuntimeException();
    $password = substr(sha1(time()), 0, 6);
    $hash = number::generate_hash($password, $this->app['salt']);
    $this->number->set_hash($hash);
    $this->em->flush();
    $body = $this->twig->render('number\generate_password.tpl',
                                [
                                 'number' => $this->number,
                                 'password' => $password
                                ]);
    $message = $this->app['Swift_Message'];
    $message->setSubject('Пароль в личный кабинет')
            ->setFrom([$this->app['email_for_reply']])
            ->setTo([$this->number->get_email()])
            ->setBody($body);
    $this->app['mailer']->send($message);
    return new Response();
  }

  public function get_dialog_generate_password(){
    if(!$this->user->check_access('numbers/generate_password'))
      throw new RuntimeException();
    return $this->twig->render('number\get_dialog_generate_password.tpl', ['number' => $this->number]);
  }
}