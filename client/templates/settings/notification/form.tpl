{% extends "private.tpl" %}

{% set rules = number.get_notification_rules() %}

{% block content %}
<div class="row">
  <div class="col-md-6">
    <div class="ibox-content">
      <div class="row">
        <div class="col-lg-12">
          <h2>Изменение правил оповещения</h2>
          <form method="post">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="cellphone"{% if rules.cellphone %} checked{% endif %}> Оповещение по сотовому телефону
              </label>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="email"{% if rules.email %} checked{% endif %}> Оповещение по email
              </label>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary pull-right">Сохранить</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>
{% endblock %}