{% extends "default.tpl" %}

{% block component %}
<div class="col-12 col-sm-6 col-lg-6">
  <form action="/enter/" method="post" class="form-horizontal">
    <legend>Вход в систему</legend>
    <fieldset>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="login">Пользователь</label>
        <div class="col-lg-9">
          <input class="form-control" type="text" id="login" name="login"{% if login %} value="{{ login }}"{% endif %}{% if error != 'WRONG_PASSWORD' %} autofocus{% endif %} required>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="password">Пароль</label>
        <div class="col-lg-9">
          <input class="form-control" type="password" id="password" name="password" {% if password %} value="{{ password }}"{% endif %}{% if error == 'WRONG_PASSWORD' %} autofocus{% endif %} required>
        </div>
      </div>
      {% if error == 'USER_NOT_EXIST' %}
        <div class="alert alert-danger" role="alert">Пользователя с таким именем не существует</div>
      {% endif %}
      {% if error == 'WRONG_PASSWORD' %}
        <div class="alert alert-danger" role="alert">Пароль не верен</div>
      {% endif %}
      <div class="form-group">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary pull-right">Войти</button>
        </div>
      </div>
    </fieldset>
  </form>
</div>
{% endblock %}