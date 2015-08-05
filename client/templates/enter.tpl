{% extends "public.tpl" %}

{% block content %}
<div id="content" class="col-md-5 col-sm-6 col-lg-4">
  <div class="row">
    <div class="login-box">
      <form class="form-horizontal login" action="/login/" method="post">
        <legend>Вход в личный кабинет</legend>
        <fieldset class="col-sm-12">
          <div class="form-group">
            <div class="input-group col-sm-12">
              <input type="text" class="form-control" name="login" placeholder="Логин" autofocus>
              <span class="input-group-addon"></span>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group col-sm-12">
              <input type="password" class="form-control" name="password" placeholder="Пароль">
              <span class="input-group-addon"></span>
            </div>
          </div>
          <div class="row">
            <button class="btn btn-primary col-xs-6" type="submit">Войти</button>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>
<div id="content" class="col-sm-6">
  <div class="control row">
    <legend>Дополнительные действия</legend>
    <div class="input-group col-sm-12">
      <a href="/registration/" class="btn btn-link">Заявка на доступ</a>
    </div>
    <div class="input-group col-sm-12">
      <a href="/recovery/" class="btn btn-link">Востановление пароля</a>
    </div>
    <div class="input-group col-sm-12">
      <a href="/arrears/" class="btn btn-link">Просмотр задолжености</a>
    </div>
  </div>
</div>
{% endblock %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock %}