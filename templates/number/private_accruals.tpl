{% extends "default.tpl" %}
{% set number = response.number %}
{% set accruals = number.get_accruals() %}
{% block component %}
  <div class="row">
    <div class="col-md-6">
      <h4>{{ number.get_fio() }}</h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <table class="table">
        <thead>
          <td>Месяц</td>
          <td>Услуга</td>
          <td>Единица</td>
          <td>Тариф</td>
          <td>ИНД</td>
          <td>ОДН</td>
          <td>Сумма ИНД</td>
          <td>Сумма ОДН</td>
          <td>Сумма всего</td>
          <td>Перерасчет</td>
          <td>Льготы</td>
          <td>Итого</td>
        </thead>
        {% for accrual in accruals %}
          <tr>
            <td>{{ accrual.get_time()|date('m.Y') }}</td>
            <td>{{ accrual.get_service() }}</td>
            <td>{{ accrual.get_unit() }}</td>
            <td>{{ accrual.get_tarif() }}</td>
            <td>{{ accrual.get_ind() }}</td>
            <td>{{ accrual.get_odn() }}</td>
            <td>{{ accrual.get_sum_ind() }}</td>
            <td>{{ accrual.get_sum_odn() }}</td>
            <td>{{ accrual.get_sum_total() }}</td>
            <td>{{ accrual.get_recalculation() }}</td>
            <td>{{ accrual.get_facilities() }}</td>
            <td>{{ accrual.get_total() }}</td>
          </tr>
        {% endfor %}
      </table>
    </div>
  </div>
{% endblock component %}