{% extends "ajax.tpl" %}
{% set group = response.group %}
{% block js %}
    $('.group[group = {{ group.get_id() }}] .group-information').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% include '@user/build_group_content.tpl' %}
{% endblock html %}