{% extends "ajax.tpl" %}
{% set user = component.users[0] %}
{% block js %}
    $('.user[user = {{user.id}}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="user-content unstyled">
        <li>
            <ul class="nav nav-pills">
                <li><a class="get_user_profile">Профиль</a></li>
            </ul>
        </li>
        <li class="number-information">
            {% include '@user/build_user_content.tpl' %}
        </li>
    </ul>
{% endblock html %}