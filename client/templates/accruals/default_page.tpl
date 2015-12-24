{% extends "private.tpl" %}

{% block content %}

{% for month, accruals in number.get_accruals() %}
<div class="row">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>{{ month|date('m.Y') }}</h5>
      </div>
      <div class="ibox-content">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
              {% for column in columns %}
              <th>{{ column }}</th>
              {% endfor %}
              </tr>
            </thead>
            <tbody>
            {% for accrual in accruals %}
              <tr>
              {% for ac in accrual|split(';') %}
                <td data-title="{{ columns[loop.index0] }}" scope="row">{{ ac }}</td>
              {% endfor %}
              </tr>
            {% endfor %}
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
{% endfor %}
{% endblock %}