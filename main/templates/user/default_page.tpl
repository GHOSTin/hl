{% extends "default.tpl" %}

{% block component %}
    <div class="btn-group">
        <button class="btn btn-default active get_users">Пользователи</button>
        <button class="btn btn-default get_groups">Группы</button>
    </div>
    <div class="btn-group">
      <a href="/users/access/" class="btn btn-default" target="_blank">Отчет по правам доступа</a>
    </div>
    <div class="workspace"></div>
{% endblock %}

{% block javascript %}
    <script src="/js/user.js"></script>
{% endblock %}

{% block css %}
    <link rel="stylesheet" href="/css/user.css" >
{% endblock %}
