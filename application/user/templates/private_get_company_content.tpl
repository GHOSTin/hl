{% extends "ajax.tpl" %}
{% set user = component.user %}
{% set company = component.company %}
{% set profiles = component.profiles %}
{% block js %}
    $('.user[user = {{ user.get_id() }}] .company[company = {{ company.get_id() }}]').append(get_hidden_content());
{% endblock js %}
{% block html %}
    <ul class="company-content unstyled">
        <li class="company-content-content">
            <ul class="unstyled">
                {% include '@user/build_profiles.tpl' %}
            </ul>
        </li>
    </ul>
{% endblock html %}