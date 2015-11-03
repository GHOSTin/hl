{% extends "private.tpl" %}

{% block content %}
<div class="row">
  <div class="col-md-7">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>Создание запроса</h5>
      </div>
      <div class="ibox-content">
        <form method="post">
          <p>Подробно опишите вашу проблему в поле ниже. Диспетчер обработает ваш запрос.</p>
          <div class="form-group">
            <textarea class="form-control" rows="3" name="description"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
      </div>
    </div>
  </div>
</div>
{% endblock %}