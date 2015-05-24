{% extends "default.tpl" %}

{% block component %}
  <div class="row">
    <div class="col-md-6">
      <h4>{{ number.get_fio() }}</h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      {% for month, accruals in number.get_sort_accruals() %}
        <h3>{{ month|date('m.Y') }}</h3>
        <table class="table">
          <tdead>
            <thead>
              <tr>
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
          {% for accrual in accruals %}
            <tr>
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
      {% endfor %}
    </div>
  </div>
{% endblock component %}