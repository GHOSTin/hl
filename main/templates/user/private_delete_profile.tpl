{% extends "ajax.tpl" %}
{% block js %}
    $('.user[user = {{ request.take_get('user_id') }}] .company[company = {{ request.take_get('company_id') }}] .profile[profile = "{{ request.take_get('profile') }}"]').remove();
{% endblock js %}