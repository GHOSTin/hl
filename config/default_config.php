<?php namespace config;

class default_config{

  /**
  * Таймзона
  */
  const php_timezone = 'Asia/Yekaterinburg';

  // db option
  const db_name = 'test';
  const db_host = 'localhost';
  const db_user = 'test';
  const db_password = 'test';
  // auth option
  const authSalt = 'salt';

  const status = 'production';

  /**
   * Директория файлов для логов.
   * Должна иметь абсолютный путь.
   * Заканчиваться слешем.
   */
  const logs_directory = '/var/log/';

  /**
  * Email, используемый для ответа
  */
  const email_for_reply = 'mail@example.com';
  const email_for_reply_password = 'ZYtLjdthz.Ct,t';

  const chat_enable = false;
  const chat_host = 'example.com';
  const chat_port = 3000;

  const debt_limit = 10000;
  const accrual_columns = 'Услуга;Единица;Тариф;ИНД;ОДН;Сумма ИНД;Сумма ОДН;Начисленно;Перерасчет;Льготы;Итого';
  const site_url = 'http://mlsco.ru';
}