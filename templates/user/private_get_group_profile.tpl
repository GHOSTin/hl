{% extends "ajax.tpl" %}
{% set group = response.group %}
{% block js %}
    $('.group-information').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="list-unstyled">
        {% include '@user/build_group_content.tpl' %}
    </ul>
{% endblock html %}