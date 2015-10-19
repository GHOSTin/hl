{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-12">
    <h3>Информация о доме {{ house.get_address() }}</h3>
    <ul>
      {% for outage in house.get_outages() %}
      <li>
          <div>c {{ outage.get_begin()|date("d.m.Y") }} по {{ outage.get_target()|date("d.m.Y") }} {{ outage.get_category().get_name() }}: {{ outage.get_description() }}</div>
          <div>Диспетчер: {{ outage.get_user().get_fio() }}</div>
          <div class="row">
            <div class="col-md-6">
              <h5>Дома</h5>
              <ul>
                {% for house in outage.get_houses() %}
                <li>{{ house.get_address() }} </li>
                {% endfor %}
              </ul>
            </div>
            <div class="col-md-6">
              <h5>Исполнители</h5>
              <ul>
                {% for user in outage.get_performers() %}
                <li>{{ user.get_fio() }} </li>
                {% endfor %}
              </ul>
            </div>
          </div>
      </li>
      {% endfor %}
    </ul>
  </div>
</div>
{% endblock %}