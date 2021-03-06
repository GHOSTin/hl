{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-12">
    <h3>Информация о доме {{ house.get_address() }}</h3>
    <ul>
      {% for outage in house.get_outages() %}
      <li>
        <div><u>c {{ outage.get_begin()|date("d.m.Y") }} по {{ outage.get_target()|date("d.m.Y") }} {{ outage.get_category().get_name() }}</u></div>
        <p>{{ outage.get_description() }} </p>
        <div class="row">
          <div class="col-md-6">
            <div>Диспетчер: {{ outage.get_user().get_fio() }}</div>
            {% if outage.get_performers() is not empty %}
            <h5>Исполнители</h5>
            <ul>
              {% for user in outage.get_performers() %}
              <li>{{ user.get_fio() }} </li>
              {% endfor %}
            </ul>
            {% endif %}
          </div>
          <div class="col-md-6">
            {% if outage.get_houses() is not empty %}
            <h5>Дома</h5>
            <ul>
              {% for house in outage.get_houses() %}
              <li>{{ house.get_address() }} </li>
              {% endfor %}
            </ul>
            {% endif %}
          </div>
        </div>
      </li>
      {% endfor %}
    </ul>
  </div>
</div>
{% endblock %}