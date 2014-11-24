{% extends "default.tpl" %}

{% block component %}
    <p>
      <a class="btn btn-success get_dialog_clear_logs">Очистить логи</a>
    </p>
    <table class="table table-striped">
      <thead>
        <tr>
          <td>Время</td>
          <td>Пользователь</td>
          <td>IP</td>
        </tr>
      </thead>
      <tbody>
        {% for session in sessions %}
        <tr>
            <td>{{ session.get_time()|date('H:i d.m.Y') }}</td>
            <td>{{ session.get_user().get_lastname() }} {{ session.get_user().get_firstname() }} {{ session.get_user().get_middlename() }}</td>
            <td>{{ session.get_ip() }}</td>
        </tr>
        {% endfor %}
      </tbody>
    </table>
{% endblock component %}

{% block javascript %}
    <script src="/js/user.js"></script>
{% endblock javascript %}