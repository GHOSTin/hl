{% extends "ajax.tpl" %}
{% set group = component.group %}
{% block js %}
    $('.group[group = {{ group.get_id() }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="group-content list-unstyled">
        <li>
            <ul class="nav nav-pills">
                <li><a class="get_group_profile">Профиль</a></li>
                <li><a class="get_group_users">Пользователи</a></li>
            </ul>
        </li>
        <li class="group-information">
            {% include '@user/build_group_content.tpl' %}
        </li>
    </ul>
{% endblock html %}