{% extends "private.tpl" %}

{% block content %}
<div class="content row">
  <div class="row">
    <div class="col-md-6">
      <form method="post">
        <p>Подробно опишите вашу проблему в поле ниже. Диспетчер обработает ваш запрос.</p>
        <div class="form-group">
          <textarea class="form-control" rows="3" name="description"></textarea>
        </div>
        <button type="submit" class="btn btn-default">Отправить</button>
      </form>
    </div>
  </div>
</div>
{% endblock %}