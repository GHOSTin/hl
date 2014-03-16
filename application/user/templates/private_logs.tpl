{% extends "default.tpl" %}
{% set sessions = component.sessions %}
{% block component %}
    <table class="table table-striped">
        <tr>
            <td>Время</td>
            <td>Пользователь</td>
            <td>IP</td>
        </tr>
        {% for session in sessions %}
        <tr>
            <td>{{ session.get_time()|date('h:i d.m.Y') }}</td>
            <td>{{ session.get_user().get_lastname() }} {{ session.get_user().get_firstname() }} {{ session.get_user().get_middlename() }}</td>
            <td>{{ session.get_ip() }}</td>
        </tr>
        {% endfor %}
    </table>
{% endblock component %}