{% extends "ajax.tpl" %}

{% block js %}
    $('.group[group = {{ group.get_id() }}] .group-users').html(get_hidden_content())
{% endblock js %}

{% block html %}
    {% include 'user/build_group_users.tpl' %}
{% endblock html %}