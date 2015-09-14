{% extends "default.tpl" %}

{% block component %}
  <div class="row">
    <div class="col-md-12">
      <h4>История изменения контактных данных лецевого счета №{{ number.get_number() }}</h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <table class="table">
        <thead>
          <th>Время</th>
          <th>ФИО</th>
          <th>Телефон</th>
          <th>Сотовый</th>
          <th>Email</th>
          <th>Изменил</th>
        </thead>
        <tbody>
        {% for contacts in number.get_relevance()|reverse %}
          <tr>
            <td>{{ contacts.time|date('H:i d.m.Y') }}</td>
            <td>{{ contacts.fio }}</td>
            <td>{{ contacts.telephone }}</td>
            <td>{{ contacts.cellphone }}</td>
            <td>{{ contacts.email }}</td>
            <td>{{ contacts.user }}</td>
          </tr>
        {% endfor %}
        </tbody>
      </table>
    </div>
  </div>
{% endblock %}