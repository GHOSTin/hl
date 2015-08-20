{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-12">
    <table class="table table-bordered table-striped">
      <thead>
        <td>Время</td>
        <td>Сообщение</td>
        <td>Логин</td>
        <td>IP</td>
        <td>XFF</td>
        <td>Браузер</td>
      </thead>
      {% for row in rows %}
      <tr>
        <td>{{ row.datetime.date }}</td>
        <td>{{ row.message }}</td>
        <td>{{ row.context.login }}</td>
        <td>{{ row.context.ip }}</td>
        <td>{{ row.context.xff }}</td>
        <td>{{ row.context.agent }}</td>
      </tr>
      {% endfor %}
    </table>
  </div>
</div>
{% endblock %}