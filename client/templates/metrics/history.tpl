{% extends "private.tpl" %}

{% block content %}
<div class="row content">
  <div class="col-md-12">
  <h2>История показаний<h2>
  {% for month, meterages in number.get_meterages_by_month() %}
    <h3>{{ month|date('m.Y') }}</h3>
    <table class="table">
      <thead>
        <th>Услуга</th>
        <th>Тариф1</th>
        <th>Тариф2</th>
      </thead>
      <tbody>
        {% for meterage in meterages %}
        <tr>
          <td>{{ meterage.get_service() }}</td>
          <td>{{ meterage.get_first() }}</td>
          <td>{{ meterage.get_second() }}</td>
        </tr>
        {% endfor %}
      </tbody>
    </table>
  {% endfor %}
  </div>
</div>
{% endblock %}

{% block css %}
<link rel="stylesheet" href="/css/default.css">
{% endblock %}