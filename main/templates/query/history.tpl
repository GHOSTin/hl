{% extends "default.tpl" %}

{% block component %}
  <div class="row">
    <div class="col-md-6">
      <h4>Заявка №{{ query.get_number() }}</h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
              <tr>
                <th>Время</th>
                <th>Пользователь</th>
                <th>Сообщение</th>
                <th>Контекст</th>
              </tr>
            </thead>
          <tbody>
          {% for event in query.get_history()|reverse %}
            <tr>
              <td>{{ event.time|date("H:i d.m.Y") }}</td>
              <td>{{ event.user }}</td>
              <td>{{ event.message }}</td>
              <td>{% for key, value in event.context %}
                <p>{{ key }}: {{ value }}</p>
              {% endfor %}
              </td>
            </tr>
          {% endfor %}
          </tbody>
        </table>
    </div>
  </div>
{% endblock %}