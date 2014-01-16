{% extends "default.tpl" %}
{% set letters = component.letters %}
{% block component %}
    <div class="btn-group">
        <button class="btn active get_user_letters">Пользователи</button>
        <button class="btn get_group_letters">Группы</button>
    </div>
    <div class="letters">
    {% include '@user/build_user_letters.tpl' %}
    </div>
    <div class="letter-content">
        <div>
            <a class="get_dialog_create_user">Создать нового пользователя</a>
        </div>
    </div>
{% endblock component %}
{% block javascript %}
    <script src="/?js=component.js&p=user"></script>
{% endblock javascript %}
{% block css %}
    <link rel="stylesheet" href="/?css=component.css&p=user" >
{% endblock css %}