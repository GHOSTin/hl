{% extends "private.tpl" %}

{% block content %}
<div class="content row">
  <div class="col-md-6">
    <h2>Изменение пароля</h2>
    <form id="changePasswordForm" method="post">
      <div class="form-group">
        <label for="old_password">Старый пароль</label>
        <input type="password" class="form-control" name="old_password" required autofocus>
      </div>
      <div class="form-group">
        <label for="new_password">Новый пароль</label>
        <input type="password" class="form-control" name="new_password" required>
      </div>
      <div class="form-group">
        <label for="confirm_password">Подтверждение нового пароля</label>
        <input type="password" class="form-control" name="confirm_password" required>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary pull-right">Изменить</button>
      </div>
    </div>
  </form>
</div>
{% endblock %}