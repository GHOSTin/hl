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
    </div>
{% endblock component %}