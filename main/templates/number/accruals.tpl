{% extends "default.tpl" %}

{% block component %}
  <div class="row">
    <div class="col-md-6">
      <h4>{{ number.get_address() }} {{ number.get_fio() }}</h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      {% for month, accruals in number.get_accruals() %}
        <h3>{{ month|date('m.Y') }}</h3>
        <table class="table">
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
      {% endfor %}
    </div>
  </div>
{% endblock %}