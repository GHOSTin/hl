{% extends "ajax.tpl" %}
{% set user = component.user %}
{% set company = component.company %}
{% set profile_name = component.profile_name %}
{% set rule = component.rule %}
{% set status = component.status %}
{% block js %}
    $('.user[user = {{ user.id }}] .company[company = {{ company.id }}] .profile[profile = "{{ profile_name }}"] .rule[rule = "{{ rule }}"] input').prop('checked', {% if status == true %}true{% else %}false{% endif %});
{% endblock js %}
{% block html %}
{% endblock html %}