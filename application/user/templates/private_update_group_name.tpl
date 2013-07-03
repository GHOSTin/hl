{% extends "ajax.tpl" %}
{% set group = component.group %}
{% block js %}
    $('.group[group = {{group.id}}] .group-information').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% include '@user/build_group_content.tpl' %}
{% endblock html %}