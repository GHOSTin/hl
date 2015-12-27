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

  public function get_event($event_id, $time){
    return $this->em->getRepository('domain\number2event')
        ->findByIndex($time, $this->number->get_id(), $event_id)[0];
  }

  public function get_dialog_exclude_event($event_id, $time){
    $n2e = $this->em->getRepository('domain\number2event')
                    ->findByIndex($time, $this->number->get_id(), $event_id)[0];
    return $this->twig->render('number\get_dialog_exclude_event.tpl', ['n2e' => $n2e]);
  }

  public function get_dialog_edit_event($event_id, $time){
    $n2e = $this->em->getRepository('domain\number2event')
                    ->findByIndex($time, $this->number->get_id(), $event_id)[0];
    return $this->twig->render('number\get_dialog_edit_event.tpl', ['n2e' => $n2e]);
  }

  public function edit_event($event_id, $time, $description, $files = []){
    $n2e = $this->em->getRepository('domain\number2event')
                    ->findByIndex($time, $this->number->get_id(), $event_id)[0];
    $files = $this->em->getRepository('domain\file')
                      ->findByPath(array_column($files, 'path'));
    $n2e->update($description, $files);
    $this->em->flush();
    return $n2e;
  }

  public function exclude_event($event_id, $time){
    $n2e = $this->em->getRepository('domain\number2event')
                    ->findByIndex($time, $this->number->get_id(), $event_id)[0];
    $this->number->exclude_event($n2e);
    $this->em->remove($n2e);
    $this->em->flush();
    return new Response();
  }

  public function generate_password($salt, $email, Swift_Message $message, Swift_Mailer $mailer, $site_url){
    if(!$this->user->check_access('numbers/generate_password'))
      throw new RuntimeException();
    $password = substr(sha1(time()), 0, 6);
    $hash = number::generate_hash($password, $salt);
    $this->number->set_hash($hash);
    $this->em->flush();
    $body = $this->twig->render('number\generate_password.tpl',
                                [
                                 'number' => $this->number,
                                 'password' => $password,
                                 'site_url' => $site_url
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

  public function meterages(){
    return $this->twig->render('number\meterages.tpl', [
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
    return $this->twig->render('number/build_number_fio.tpl',
                                [
                                 'number' => $this->number,
                                 'user' => $this->user
                                ]);
  }
}