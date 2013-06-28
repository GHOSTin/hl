{% extends "ajax.tpl" %}
{% set users = component.users %}
{% block js %}
    $('.letter-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="unstyled">
        {% include '@user/build_users.tpl' %}
    </ul>
{% endblock html %}