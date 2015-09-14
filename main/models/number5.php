<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use domain\user;
use domain\number;
use Swift_Message;
use Swift_Mailer;

class number5{

  private $em;
  private $twig;
  private $user;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user, $id){
    $this->twig = $twig;
    $this->em = $em;
    $this->user = $user;
    if(!$this->user->check_access('numbers/general_access'))
      throw new RuntimeException();
    $this->number = $this->em->find('domain\number', $id);
    if(is_null($this->number))
      throw new RuntimeException();
  }

  public function generate_password($salt, $email, Swift_Message $message, Swift_Mailer $mailer){
    if(!$this->user->check_access('numbers/generate_password'))
      throw new RuntimeException();
    $password = substr(sha1(time()), 0, 6);
    $hash = number::generate_hash($password, $salt);
    $this->number->set_hash($hash);
    $this->em->flush();
    $body = $this->twig->render('number\generate_password.tpl',
                                [
                                 'number' => $this->number,
                                 'password' => $password
                                ]);
    $message->setSubject('Пароль в личный кабинет')
            ->setFrom([$email])
            ->setTo([$this->number->get_email()])
            ->setBody($body);
    $mailer->send($message);
    return new Response();
  }

  public function get_dialog_contacts(){
    if(!$this->user->check_access('numbers/contacts'))
      throw new RuntimeException();
    return $this->twig->render('number\get_dialog_contacts.tpl', ['number' => $this->number]);
  }

  public function get_dialog_generate_password(){
    if(!$this->user->check_access('numbers/generate_password'))
      throw new RuntimeException();
    return $this->twig->render('number\get_dialog_generate_password.tpl', ['number' => $this->number]);
  }

  public function history(){
    return $this->twig->render('number\history.tpl', [
                                                      'number' => $this->number,
                                                      'user' => $this->user
                                                      ]);
  }

  public function update_contacts($fio, $tellphone, $cellphone, $email){
    if(!$this->user->check_access('numbers/contacts'))
      throw new RuntimeException();
    preg_match_all('/[0-9]/', $tellphone, $tellphone_matches);
    preg_match_all('/[0-9A-Za-z.@\-_]/', $email, $email_matches);
    preg_match_all('/[0-9]/', $cellphone, $matches);
    $cellphone = implode('', $matches[0]);
    if(preg_match('|^[78]|', $cellphone))
      $cellphone = substr($cellphone, 1, 10);
    $this->number->update_contacts(
                                    $this->user,
                                    $fio,
                                    implode('', $tellphone_matches[0]),
                                    $cellphone,
                                    implode('', $email_matches[0])
                                  );
    $this->em->flush();
    return $this->twig->render('number\update_number_fio.tpl',
                                [
                                 'number' => $this->number,
                                 'user' => $this->user
                                ]);
  }
}