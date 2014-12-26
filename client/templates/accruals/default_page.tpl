{% extends "private.tpl" %}

{% block content %}
<div class="row content">
  <div class="col-md-12">
    <table class="table">
      <tdead>
        <thead>
          <tr>
            <th>Месяц</th>
            <th>Услуга</th>
            <th>Единица</th>
            <th>Тариф</th>
            <th>ИНД</th>
            <th>ОДН</th>
            <th>Сумма ИНД</th>
            <th>Сумма ОДН</th>
            <th>Начисленно</th>
            <th>Перерасчет</th>
            <th>Льготы</th>
            <th>Итого</th>
          </tr>
        </thead>
      </tdead>
      <tbody>
      {% for accrual in number.get_accruals() %}
        <tr>
          <td data-title="Месяц" scope="row">{{ accrual.get_time()|date('m.Y') }}</td>
          <td data-title="Услуга" scope="row">{{ accrual.get_service() }}</td>
          <td data-title="Единица">{{ accrual.get_unit() }}</td>
          <td data-title="Тариф">{{ accrual.get_tarif() }}</td>
          <td data-title="ИНД">{{ accrual.get_ind() }}</td>
          <td data-title="ОДН">{{ accrual.get_odn() }}</td>
          <td data-title="Сумма ИНД">{{ accrual.get_sum_ind() }}</td>
          <td data-title="Сумма ОДН">{{ accrual.get_sum_odn() }}</td>
          <td data-title="Начисленно">{{ accrual.get_sum_total() }}</td>
          <td data-title="Перерасчет">{{ accrual.get_recalculation() }}</td>
          <td data-title="Льготы">{{ accrual.get_facilities() }}</td>
          <td data-title="Итого">{{ accrual.get_total() }}</td>
        </tr>
      {% endfor %}
      </tbody>
    </table>
  </div>
</div>
{% endblock %}

{% block js %}
<script>
  $(document).ready(function(){
    $('#sidebar-nav li').removeClass('active');
    $('#accruals').addClass('active');
  });
</script>
{% endblock %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock %}