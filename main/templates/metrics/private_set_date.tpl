{% set metrics = response.metrics %}
{% if metrics %}
  <table class="table table-hover">
    <thead>
    <tr>
      <th>Дата</th>
      <th>Адрес</th>
      <th>Показания</th>
    </tr>
    </thead>
    <tbody>
    {% for metric in metrics %}
      <tr>
        <td>{{ metric.get_time()|date('Y.m.d H:i:s') }}</td>
        <td>{{ metric.get_address() }}</td>
        <td>{{ metric.get_metrics()|nl2br }}</td>
      </tr>
    {% endfor %}
    </tbody>
  </table>
{% else %}
  Не найдено показаний за этот день.
{% endif %}
