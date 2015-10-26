{% extends "public.tpl" %}

{% block content %}
<div id="content" class="loginColumns animated fadeInDown">
  <div class="row">
    <div class="col-md-5">
      <form class="login" action="/login/" method="post">
        <legend>Вход в личный кабинет</legend>
        <div class="form-group">
            <input type="text" class="form-control" name="login" placeholder="Номер лицевого счета" autofocus>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Пароль">
        </div>
        <button class="btn btn-primary btn-w-m m-b" type="submit">Войти</button>
      </form>
    </div>
    <div class="col-md-7">
      <legend>Дополнительные действия</legend>
      <div class="row">
        <div class="col-xs-12 col-sm-8">
          <a href="/registration/" class="btn btn-outline btn-primary btn-block m-b">Заявка на доступ</a>
        </div>
        <div class="col-xs-12 col-sm-8">
          <a href="/recovery/" class="btn btn-outline btn-primary btn-block m-b">Востановление пароля</a>
        </div>
        <div class="col-xs-12 col-sm-8">
          <a href="/arrears/" class="btn btn-outline btn-primary btn-block m-b">Просмотр задолжености</a>
        </div>
      </div>
      <legend>Какие возможности вам доступны в личном кабинете?</legend>
      <ul>
        <li>возможность передать показания приборов учета</li>
        <li>запрос на рассмотрение и отслеживание вашей заявки</li>
        <li>оплата через интернет</li>
        <li>просмотр истории начислений и показаний приборов учета</li>
      </ul>
    </div>
  </div>
</div>
{% endblock %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock %}