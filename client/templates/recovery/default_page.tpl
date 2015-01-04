{% extends "public.tpl" %}

{% block content %}
<div id="content" class="col-md-5 col-sm-6 col-lg-4">
  <div class="row">
    <div class="col-md-12">
      <h2>Востановление пароля</h2>
      <p>Новый пароль будет выслан на привязанный к лицевому счету email.</p>
      <form action="/recovery/" method="post">
        <div class="form-group">
          <label>Введите номер лицевого счета</label>
          <input class="form-control" type="text" name="number" required autofocus>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-default">Востановить</button>
          <a href="/">
            <button type="button" class="btn btn-default">Отмена</button>
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
{% endblock %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock %}