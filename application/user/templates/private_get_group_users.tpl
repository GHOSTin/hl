{% extends "ajax.tpl" %}
{% set users = component.users %}
{% block js %}
    $('.group-information').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="unstyled">
        {% include '@user/build_group_users.tpl' %}
    </ul>
{% endblock html %}