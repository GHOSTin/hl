{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-6">
    <h2>Поиск по номеру лицевого счета</h2>
    <form method="post">
      <div class="form-group">
        <input class="form-control" type="text" name="number">
      </div>
      <button class="btn btn-default">Искать</button>
    </form>
  </div>
</div>
{% endblock %}