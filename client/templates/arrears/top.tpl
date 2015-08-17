{% if debtors is not empty %}
<div class="table-responsive well">
  <h4>Злостные неплательщики</h4>
  <table class="table table-striped table-condensed">
    <thead>
      <th>Квартира</th>
      <th>Задолженость в рублях</th>
    </thead>
    <tbody>
    {% for numbers in debtors %}
      {% for number in numbers %}
      <tr>
        <td>кв. №{{ number.get_flat().get_number() }}</td>
        <td>{{ number.get_debt()|number_format(2, '.', ' ') }}</td>
      </tr>
      {% endfor %}
    {% endfor %}
    </tbody>
  </table>
</div>
{% endif %}