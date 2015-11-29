{% extends "default.tpl" %}

{% block component %}
<div class="middle-box text-center loginscreen animated fadeInDown ibox-content">
  <form action="/enter/" method="post">
    <legend>Вход в систему</legend>
    <fieldset>
      <div class="form-group">
        <input class="form-control {% if error == 'USER_NOT_EXIST' %} error {% endif %}" type="text" id="login" placeholder="Пользователь" name="login"{% if login %} value="{{ login }}"{% endif %}{% if error != 'WRONG_PASSWORD' %} autofocus{% endif %} required>
        {% if error == 'USER_NOT_EXIST' %}
          <label id="login-error" class="error" for="login" style="display: inline-block;">Пользователя с таким именем не существует</label>
        {% endif %}
      </div>
      <div class="form-group">
        <input class="form-control {% if error == 'WRONG_PASSWORD' %} error {% endif %}" type="password" id="password" name="password" placeholder="Пароль" {% if password %} value="{{ password }}"{% endif %}{% if error == 'WRONG_PASSWORD' %} autofocus{% endif %} required>
        {% if error == 'WRONG_PASSWORD' %}
          <label id="password-error" class="error" for="password" style="display: inline-block;">Пароль не верен</label>
        {% endif %}
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary pull-right">Войти</button>
      </div>
    </fieldset>
  </form>
</div>
{% endblock %}