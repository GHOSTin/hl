{% extends "private.tpl" %}

{% block content %}
<div class="row">
  <div class="col-md-6">
    <div class="ibox-content">
      <div class="row">
        <div class="col-lg-12">
          <h2>Изменение номера сотового телефона</h2>
            <form method="post">
              <div class="form-group">
                <label for="cellphone">Номер</label>
                <input type="text" class="form-control" id="cellphone" name="cellphone" value="{% if number.get_cellphone() %}8{% endif %}{{ number.get_cellphone() }}" autofocus>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary pull-right">Сохранить</button>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>
{% endblock %}