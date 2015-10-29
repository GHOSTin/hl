{% extends "private.tpl" %}

{% block content %}
<div class="row">
  <div class="col-md-6">
    <div class="ibox-content">
      <div class="row">
        <div class="col-lg-12">
          <h2>Изменение пароля</h2>
          <form method="post">
            <div class="form-group">
              <label for="old_password">Старый пароль</label>
              <input type="password" class="form-control" id="old_password" name="old_password" required autofocus>
            </div>
            <div class="form-group">
              <label for="new_password">Новый пароль</label>
              <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
              <label for="confirm_password">Подтверждение нового пароля</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary pull-right">Изменить</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
{% endblock %}