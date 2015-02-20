{% extends "private.tpl" %}

{% block content %}
<div class="content row">
  <div class="col-md-6">
    <h2>Изменение почтового адреса</h2>
    <form method="post">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ number.get_email() }}" autofocus>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary pull-right">Сохранить</button>
      </div>
    </div>
  </form>
</div>
{% endblock %}