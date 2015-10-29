{% extends "private.tpl" %}

{% block content %}
<div class="row">
  {% if number.get_last_month_meterages() is not empty %}
    <div class="col-xs-12">
      <h2>Показания предыдущего месяца</h2>
    </div>
    {% for meterage in number.get_last_month_meterages() %}
      <div class="col-sm-4 col-lg-3 animated flipInX">
        <div class="ibox float-e-margins">
          <div class="ibox-title">
            <h5>{{ meterage.get_service() }}</h5>
          </div>
          <div class="ibox-content">
            <div class="row">
              <div class="col-md-6">
                <p class="font-bold text-navy">Тариф1</p>
                <h3 class="no-margins">{{ meterage.get_first() }}</h3>
              </div>
              <div class="col-md-6">
                <p class="font-bold text-navy">Тариф2</p>
                <h3 class="no-margins">{{ meterage.get_second() }}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    {% endfor %}
  {% endif %}
</div>
<div class="row">
  <div class="col-md-6 col-lg-5">
    <p>
      <a class="btn btn-w-m btn-outline btn-link" href="history/">
        <i class="fa fa-history"></i>
        История показаний
      </a>
    </p>
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <h2>Показания счетчиков</h2>
        <form method="post">
          <input type="hidden" class="form-control" name="address" required value="{{ number.get_flat().get_house().get_street().get_name() }}, дом {{ number.get_flat().get_house().get_number() }}, кв.{{ number.get_flat().get_number() }}">
          <div class="row">
            <div class="form-group col-md-6">
              <label>ГВС1</label>
              <input type="text"  class="form-control" name="gvs1">
            </div>
            <div class="form-group col-md-6">
              <label>ГВС2</label>
              <input type="text"  class="form-control" name="gvs2">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label>ХВС1</label>
              <input type="text"  class="form-control" name="hvs1">
            </div>
            <div class="form-group col-md-6">
              <label>ХВС2</label>
              <input type="text"  class="form-control" name="hvs2">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label>Электроэнергия (день)</label>
              <input type="text"  class="form-control" name="electrical1">
            </div>
            <div class="form-group col-md-6">
              <label>Электроэнергия (ночь)</label>
              <input type="text"  class="form-control" name="electrical2">
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Отправить</button>
          <a href="/" class="btn btn-default">Отменить</a>
        </form>
      </div>
    </div>
  </div>
</div>
{% endblock %}