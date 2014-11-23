{% extends "ajax.tpl" %}
{% set users = response.users %}
{% set group = response.group %}
{% block js %}
    $('.group[group = {{ group.get_id() }}] .group-users').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% include '@user/build_group_users.tpl' %}
{% endblock html %}