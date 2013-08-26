{% extends "default.tpl" %}
{% set letters = component.letters %}
{% block component %}
    <div class="btn-group">
        <button class="btn btn-default active get_user_letters">Пользователи</button>
        <button class="btn btn-default get_group_letters">Группы</button>
    </div>
    <div class="letters">
    {% include '@user/build_user_letters.tpl' %}
    </div>
    <div class="letter-content">
        <div>
            <a class="btn btn-link get_dialog_create_user">Создать нового пользователя</a>
        </div>
    </div>
{% endblock component %}