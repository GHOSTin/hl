{% extends "ajax.tpl" %}
{% set groups = component.groups %}
{% block js %}
    $('.letter-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="unstyled">
        {% include '@user/build_groups.tpl' %}
    </ul>
{% endblock html %}