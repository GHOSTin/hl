{% extends "default.tpl" %}
{% set letters = response.letters %}
{% block component %}
    <div class="btn-group">
        <button class="btn btn-default active get_user_letters">Пользователи</button>
        <button class="btn btn-default get_group_letters">Группы</button>
    </div>
    <a href="/user/logs">Логи</a>
    <div class="letters">
    {% include '@user/build_user_letters.tpl' %}
    </div>
    <div class="letter-content">
        <div>
            <a class="btn btn-link get_dialog_create_user">Создать нового пользователя</a>
        </div>
    </div>
{% endblock component %}
{% block javascript %}
    <script src="/js/user.js"></script>
{% endblock javascript %}
{% block css %}
    <link rel="stylesheet" href="/css/user.css" >
{% endblock css %}
