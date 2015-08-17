{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-6">
  <table class="table">
    <thead>
      <th>Параметр</th>
      <th>Значение</th>
    </thead>
    <tbody>
      {% for key, value in conf %}
        <tr>
          <td>{{ key }}</td>
          <td>{{ value }}</td>
        </tr>
      {% endfor %}
    </tbody>
  </table>
  </div>
</div>
{% endblock %}