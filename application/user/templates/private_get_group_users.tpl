{% extends "ajax.tpl" %}
{% set users = component.users %}
{% set group = component.group %}
{% block js %}
    $('.group[group = {{ group.get_id() }}] .group-information').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <a class="btn btn-link get_dialog_add_user">Добавить</a>
    <ul class="list-unstyled group-users">
        {% include '@user/build_group_users.tpl' %}
    </ul>
{% endblock html %}