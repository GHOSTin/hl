{% extends "private.tpl" %}

{% block content %}
<div class="content row">
  <div class="col-md-5">
    <form method="post">
      <input type="hidden" class="form-control" name="address" required value="{{ number.get_flat().get_house().get_street().get_name() }}, дом {{ number.get_flat().get_house().get_number() }}, кв.{{ number.get_flat().get_number() }}">
      <h2>Показания счетчиков</h2>
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
{% endblock %}

{% block css %}
<link rel="stylesheet" href="/css/default.css">
{% endblock %}