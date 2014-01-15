{% extends "ajax.tpl" %}
{% set user = component.user %}
{% set company = component.company %}
{% set profile_name = component.profile_name %}
{% set profile = component.profile %}
{% set restriction_name = component.restriction_name %}
{% set rest = profile['restrictions'][restriction_name] %}
{% set items = component.items %}
{% block js %}
    $('.user[user = {{ user.id }}] .company[company = {{ company.id }}] .profile[profile = "{{ profile_name }}"] .restriction[restriction = "{{ restriction_name }}"]').append(get_hidden_content());
{% endblock js %}
{% block html %}
    <ul class="restriction-content list-unstyled">
        {% if restriction_name == 'departments' %}
            {% for department in items %}
                <li class="item" item="{{ department.id }}"><input type="checkbox"{% if department.id in rest %}checked=""{% endif %}> {{ department.name }}</li>
            {% endfor %}
        {% endif %}
        {% if restriction_name == 'worktypes' %}
            {% for worktype in items %}
                <li class="item" item="{{ worktype.id }}"><input type="checkbox"{% if worktype.id in rest %}checked=""{% endif %}> {{ worktype.name }}</li>
            {% endfor %}
        {% endif %}
    </ul>
{% endblock html %}