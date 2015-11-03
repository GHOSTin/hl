{% extends "private.tpl" %}

{% block content %}
<div class="row">
  <div class="col-md-12">
  <h2>История показаний</h2>
    <div class="tabs-container">
      <div class="tabs-left">
        <ul class="nav nav-tabs">
          {% for month, meterages in number.get_meterages_by_month() %}
          <li><a data-toggle="tab" href="#tab-{{ month }}"> {{ month|date('m.Y') }}</a></li>
          {% endfor %}
        </ul>
        <div class="tab-content ">
          {% for month, meterages in number.get_meterages_by_month() %}
          <div id="tab-{{ month }}" class="tab-pane">
            <div class="panel-body">
              <div class="table-responsive">
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
              </div>
            </div>
          </div>
          {% endfor %}
        </div>
      </div>
    </div>
  </div>
</div>
{% endblock %}

{% block js %}
  <script>
    $('.nav.nav-tabs a:first').tab('show');
  </script>
{% endblock %}