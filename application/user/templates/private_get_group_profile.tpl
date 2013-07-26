{% extends "ajax.tpl" %}
{% set group = component.groups[0] %}
{% block js %}
    $('.group-information').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="unstyled">
        {% include '@user/build_group_content.tpl' %}
    </ul>
{% endblock html %}