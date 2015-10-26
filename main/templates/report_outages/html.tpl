{% extends "default.tpl" %}

{% block component %}
<table class="table table-bordered">
  <tbody>
{% for outage in outages %}
  {% if loop.index0 % 10 == 0 %}
  <tr>
    <th>Начальная дата</th>
    <th>Планируемая дата</th>
    <th>Тип работ</th>
    <th>Описание</th>
    <th>Дома</th>
    <th>Диспетчер</th>
    <th>Исполнители</th>
  </tr>
  {% endif %}
  <tr>
    <td>{{ outage.get_begin()|date("H:i d.m.Y") }}</td>
    <td>{{ outage.get_target()|date("H:i d.m.Y") }}</td>
    <td>{{ outage.get_category().get_name() }}</td>
    <td>{{ outage.get_description() }}</td>
    <td>
    {% if outage.get_houses() is not empty %}
      <ul>
      {% for house in outage.get_houses() %}
        <li>{{ house.get_address() }}</li>
      {% endfor %}
      </ul>
    {% endif %}
    </td>
    <td>{{ outage.get_user().get_fio() }}</td>
    <td>
    {% if outage.get_performers() is not empty %}
      <ul>
      {% for performer in outage.get_performers() %}
        <li>{{ performer.get_fio() }}</li>
      {% endfor %}
      </ul>
    {% endif %}
    </td>
  </tr>
{% endfor %}
  <tbody>
</table>
{% endblock %}