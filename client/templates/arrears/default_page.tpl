{% extends "public.tpl" %}

{% block content %}
<div class="col-md-6">
  <h2>Просмотр задолжености</h2>
  <div class="form-group">
    <select class="form-control streets">
      <option value="0" selected>Выберите улицу</option>
    {% for street in streets %}
      <option value="{{ street.get_id() }}">{{ street.get_name() }}</option>
    {% endfor %}
    </select>
  </div>
  <div class="form-group">
    <select class="form-control houses" disabled="disabled">
      <option>Ожидание....</option>
    </select>
  </div>
  <div class="form-group">
    <select class="form-control flats" disabled="disabled">
      <option>Ожидание....</option>
    </select>
  </div>
  <div class="flat"></div>
  <div class="top"></div>
  <p>
    <a href="/">Вернуться на главную</a>
  </p>
</div>
{% endblock %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock %}

{% block js %}
<script src="/js/arrears.js"></script>
{% endblock %}